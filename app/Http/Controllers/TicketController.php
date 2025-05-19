<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\TicketMessage;
use Illuminate\Support\Facades\Auth;

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
        $data = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'priority' => 'required|in:baja,media,alta,critica',
            'category_id' => 'required|exists:ticket_categories,id',
        ]);

        $data['user_id'] = Auth::id();
        $data['status'] = 'abierto';

        Ticket::create($data);

        return redirect()->route('tickets.list')->with('success', 'Ticket creado correctamente.');
    }

    public function detail($id)
    {
        $ticket = Ticket::with('category', 'user')->findOrFail($id);

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
