<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\TicketMessage;
use App\Models\TicketImage;
use App\Models\TicketMessageAttachment;
use App\Services\TicketAutoResolveService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    public function list(TicketAutoResolveService $ticketAutoResolveService)
    {
        if (Cache::add('tickets:auto-resolve:last-run', true, now()->addMinutes(15))) {
            $ticketAutoResolveService->resolveStaleTickets();
        }

        $tickets = Ticket::with('category', 'user.crew')
            ->where(function ($query) {
                $query->whereNotIn('status', ['cerrado', 'resuelto'])
                    ->orWhere(function ($q) {
                        $q->whereIn('status', ['cerrado', 'resuelto'])
                          ->where('closed_at', '>=', now()->subDays(10));
                    });
            })
            ->latest()
            ->get();

        return view('tickets.show', compact('tickets'));
    }

    public function form()
    {
        $categories = TicketCategory::orderBy('id')->get();
        return view('tickets.new', compact('categories'));
    }

    public function save(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'priority' => 'required|in:baja,media,alta,critica',
            'category_id' => 'required|exists:ticket_categories,id',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $ticket = Ticket::create([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'category_id' => $request->category_id,
            'user_id' => Auth::id(),
            'status' => 'abierto',
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                try {
                    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

                    Storage::putFileAs('tickets', $image, $filename);

                    TicketImage::create([
                        'ticket_id' => $ticket->id,
                        'path' => $filename,
                        'original_name' => $image->getClientOriginalName(),
                    ]);
                } catch (\Exception $e) {
                    Log::error('Error al guardar imagen de ticket', [
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }

        return redirect()->route('tickets.list')->with('success', 'Ticket creado correctamente.');
    }

    public function detail($id)
    {
        $ticket = Ticket::with(['category', 'user', 'images', 'messages.attachments', 'messages.user'])->findOrFail($id);

        return view('tickets.edit', compact('ticket'));
    }

    public function storeMessage(Request $request, Ticket $ticket)
    {
        if (Auth::user()->role_id !== 1 && in_array($ticket->status, ['cerrado', 'resuelto'])) {
            return redirect()->back()->with('error', 'No puedes añadir mensajes a un ticket cerrado o resuelto.');
        }

        $request->validate([
            'message' => 'required|string|max:2000',
            'attachments' => 'nullable|array|max:5',
            'attachments.*' => 'file|mimes:jpeg,png,jpg,gif,webp,mp4,mov,avi,wmv|max:20480',
        ]);

        $message = TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                try {
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                    Storage::putFileAs('tickets', $file, $filename);

                    TicketMessageAttachment::create([
                        'ticket_message_id' => $message->id,
                        'path' => $filename,
                        'original_name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getMimeType(),
                        'file_size' => $file->getSize(),
                    ]);
                } catch (\Exception $e) {
                    Log::error('Error al guardar adjunto de mensaje', [
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Mensaje enviado.');
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $user = Auth::user();

        $request->validate([
            'status' => 'required|string|in:abierto,en progreso,esperando respuesta,resuelto,cerrado'
        ]);

        if ($user->role_id != 1) {

            if (!in_array($request->status, ['abierto', 'resuelto'])) {
                return redirect()->back()->with('error', 'No tienes permisos para cambiar a este estado.');
            }
        }

        $oldStatus = $ticket->status;
        $ticket->status = $request->status;

        if (in_array($request->status, ['cerrado', 'resuelto']) && !in_array($oldStatus, ['cerrado', 'resuelto'])) {
            $ticket->closed_at = now();
        }

        if (in_array($oldStatus, ['cerrado', 'resuelto']) && !in_array($request->status, ['cerrado', 'resuelto'])) {
            $ticket->closed_at = null;
        }

        $ticket->save();

        return redirect()->back()->with('success', 'Estado actualizado.');
    }

    public function getImage($filename)
    {
        $user = Auth::user();
        $ticketImage = TicketImage::where('path', $filename)->first();

        if (!$ticketImage) {
            abort(404);
        }

        $ticket = $ticketImage->ticket;

        if ($user->role_id != 1 && $ticket->user_id != $user->id) {
            abort(403, 'No tienes permiso para acceder a este recurso.');
        }

        $path = 'tickets/' . $filename;

        if (!Storage::exists($path)) {
            abort(404);
        }

        $file = Storage::get($path);
        $mimeType = Storage::mimeType($path);

        return response($file, 200)->header('Content-Type', $mimeType);
    }

    public function getAttachment($filename)
    {
        $user = Auth::user();

        $attachment = TicketMessageAttachment::where('path', $filename)->first();
        $ticketImage = TicketImage::where('path', $filename)->first();

        if (!$attachment && !$ticketImage) {
            abort(404);
        }

        if ($attachment) {
            $ticket = $attachment->message->ticket;
        } else {
            $ticket = $ticketImage->ticket;
        }

        if ($user->role_id != 1 && $ticket->user_id != $user->id) {
            abort(403, 'No tienes permiso para acceder a este recurso.');
        }

        $path = 'tickets/' . $filename;

        if (!Storage::exists($path)) {
            abort(404);
        }

        $file = Storage::get($path);
        $mimeType = Storage::mimeType($path);

        return response($file, 200)->header('Content-Type', $mimeType);
    }
}
