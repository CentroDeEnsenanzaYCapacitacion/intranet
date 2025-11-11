<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\TicketMessage;
use App\Models\TicketImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    public function list()
    {
        $tickets = Ticket::with('category', 'user')
            ->where(function ($query) {
                $query->whereNotIn('status', ['cerrado', 'resuelto'])
                    ->orWhere(function ($q) {
                        $q->whereIn('status', ['cerrado', 'resuelto'])
                          ->where('updated_at', '>=', now()->subMonth());
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

        // Crear ticket sin el campo 'images'
        $ticket = Ticket::create([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'category_id' => $request->category_id,
            'user_id' => Auth::id(),
            'status' => 'abierto',
        ]);

        // Procesar imágenes si existen
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                try {
                    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    
                    // Guardar en storage/app/tickets/
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
        $ticket = Ticket::with('category', 'user', 'images')->findOrFail($id);

        return view('tickets.edit', compact('ticket'));
    }

    public function storeMessage(Request $request, Ticket $ticket)
    {
        // Validar que usuarios no-admin no puedan escribir en tickets cerrados/resueltos
        if (Auth::user()->role_id !== 1 && in_array($ticket->status, ['cerrado', 'resuelto'])) {
            return redirect()->back()->with('error', 'No puedes añadir mensajes a un ticket cerrado o resuelto.');
        }

        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        return redirect()->back()->with('success', 'Mensaje enviado.');
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $request->validate([
            'status' => 'required|string|in:abierto,en progreso,esperando respuesta,resuelto,cerrado'
        ]);

        $ticket->status = $request->status;
        $ticket->save();

        return redirect()->back()->with('success', 'Estado actualizado.');
    }

    public function getImage($filename)
    {
        $path = 'tickets/' . $filename;
        
        if (!Storage::exists($path)) {
            abort(404);
        }

        $file = Storage::get($path);
        $mimeType = Storage::mimeType($path);

        return response($file, 200)->header('Content-Type', $mimeType);
    }
}
