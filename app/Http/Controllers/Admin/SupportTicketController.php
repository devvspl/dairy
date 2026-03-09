<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use Illuminate\Http\Request;

class SupportTicketController extends Controller
{
    public function index(Request $request)
    {
        $query = SupportTicket::with('user')->orderBy('created_at', 'desc');

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('priority') && $request->priority) {
            $query->where('priority', $request->priority);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $tickets = $query->paginate(15);

        return view('admin.support-tickets.index', compact('tickets'));
    }

    public function show(SupportTicket $supportTicket)
    {
        $supportTicket->load('user');
        return view('admin.support-tickets.show', compact('supportTicket'));
    }

    public function update(Request $request, SupportTicket $supportTicket)
    {
        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
            'priority' => 'required|in:low,medium,high,urgent',
            'admin_reply' => 'nullable|string',
        ]);

        if ($request->filled('admin_reply') && $supportTicket->admin_reply !== $request->admin_reply) {
            $validated['replied_at'] = now();
        }

        $supportTicket->update($validated);

        return redirect()->route('admin.support-tickets.show', $supportTicket)
            ->with('success', 'Ticket updated successfully!');
    }

    public function destroy(SupportTicket $supportTicket)
    {
        $supportTicket->delete();

        return redirect()->route('admin.support-tickets.index')
            ->with('success', 'Ticket deleted successfully!');
    }
}
