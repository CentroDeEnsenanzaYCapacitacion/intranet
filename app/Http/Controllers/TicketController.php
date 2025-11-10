<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\TicketMessage;
use App\Models\TicketImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
            // Determinar ruta según ambiente
            if (app()->environment('production')) {
                // En producción: /public_html/intranet/
                $uploadPath = str_replace(
                    '/intranet_dev/public',
                    '/public_html/intranet',
                    public_path('uploads/tickets')
                );
            } elseif (app()->environment('development')) {
                // En desarrollo: /public_html/intranet_dev/
                $uploadPath = str_replace(
                    '/intranet_dev/public',
                    '/public_html/intranet_dev',
                    public_path('uploads/tickets')
                );
            } else {
                // En local
                $uploadPath = public_path('uploads/tickets');
            }

            // Verificar que la carpeta existe
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0775, true);
            }

            foreach ($request->file('images') as $image) {
                try {
                    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

                    // Mover archivo
                    $image->move($uploadPath, $filename);

                    TicketImage::create([
                        'ticket_id' => $ticket->id,
                        'path' => 'uploads/tickets/' . $filename,
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
            'status' => 'required|string|in:abierto,en progreso,resuelto,cerrado'
        ]);

        $ticket->status = $request->status;
        $ticket->save();

        return redirect()->back()->with('success', 'Estado actualizado.');
    }

}
