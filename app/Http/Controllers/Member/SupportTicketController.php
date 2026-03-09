<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportTicketController extends Controller
{
    public function index()
    {
        $tickets = SupportTicket::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('member.support-tickets.index', compact('tickets'));
    }

    public function create()
    {
        return view('member.support-tickets.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        $validated['user_id'] = Auth::id();

        SupportTicket::create($validated);

        return redirect()->route('member.support-tickets.index')
            ->with('success', 'Support ticket created successfully!');
    }

    public function show(SupportTicket $supportTicket)
    {
        // Ensure user can only view their own tickets
        if ($supportTicket->user_id !== Auth::id()) {
            abort(403);
        }

        return view('member.support-tickets.show', compact('supportTicket'));
    }
}
