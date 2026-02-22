<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactInquiry;
use Illuminate\Http\Request;

class ContactInquiryController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactInquiry::query()->latest();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        $inquiries = $query->paginate(20);
        $stats = [
            'total' => ContactInquiry::count(),
            'new' => ContactInquiry::new()->count(),
            'read' => ContactInquiry::read()->count(),
            'replied' => ContactInquiry::replied()->count(),
            'closed' => ContactInquiry::closed()->count(),
        ];

        return view('admin.contact-inquiries.index', compact('inquiries', 'stats'));
    }

    public function show(ContactInquiry $contactInquiry)
    {
        // Mark as read if it's new
        if ($contactInquiry->status === 'new') {
            $contactInquiry->update(['status' => 'read']);
        }

        return view('admin.contact-inquiries.show', compact('contactInquiry'));
    }

    public function updateStatus(Request $request, ContactInquiry $contactInquiry)
    {
        $request->validate([
            'status' => 'required|in:new,read,replied,closed',
            'admin_notes' => 'nullable|string',
        ]);

        $contactInquiry->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes ?? $contactInquiry->admin_notes,
        ]);

        return redirect()->back()->with('success', 'Inquiry status updated successfully.');
    }

    public function destroy(ContactInquiry $contactInquiry)
    {
        $contactInquiry->delete();
        return redirect()->route('admin.contact-inquiries.index')->with('success', 'Inquiry deleted successfully.');
    }
}
