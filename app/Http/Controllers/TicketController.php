<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\TicketMessage;
use App\Models\TicketImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    public function list()
    {
        $tickets = Ticket::with('category', 'user')->latest()->get();
        return view('tickets.show', compact('tickets'));
    }

    public function form()
    {
        $categories = TicketCategory::orderBy('name')->get();
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
            $uploadPath = public_path('uploads/tickets');
            
            // Verificar que la carpeta existe y tiene permisos
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0775, true);
            }
            
            foreach ($request->file('images') as $image) {
                try {
                    // Guardar directamente en public/uploads/tickets
                    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->move($uploadPath, $filename);
                    
                    TicketImage::create([
                        'ticket_id' => $ticket->id,
                        'path' => 'uploads/tickets/' . $filename,
                        'original_name' => $image->getClientOriginalName(),
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Error al guardar imagen de ticket', [
                        'error' => $e->getMessage(),
                        'path' => $uploadPath,
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
