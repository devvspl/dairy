<?php

namespace App\Http\Controllers\Admin;

use App\Exports\SubscriptionsExport;
use App\Http\Controllers\Controller;
use App\Models\ExportLog;
use App\Models\UserSubscription;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class UserSubscriptionController extends Controller
{
    /**
     * Display a listing of subscriptions
     */
    public function index(Request $request)
    {
        $query = UserSubscription::with(['user', 'membershipPlan', 'location'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Group by user — fetch all (no pagination) then group
        $subscriptions = $query->get()->groupBy('user_id');
        $locations = \App\Models\Location::orderBy('name')->get();

        return view('admin.subscriptions.index', compact('subscriptions', 'locations'));
    }

    /**
     * Export subscriptions to Excel and store the file
     */
    public function export(Request $request)
    {
        $filters  = $request->only(['status', 'payment_status', 'location_id', 'search']);
        $exporter = new SubscriptionsExport($filters);

        $filename = 'subscriptions-' . now()->format('d-M-Y-His') . '.xlsx';
        $path     = 'exports/subscriptions/' . $filename;

        Excel::store($exporter, $path, 'public_folder');

        ExportLog::create([
            'type'          => 'subscription',
            'filename'      => $filename,
            'path'          => $path,
            'filter_status' => implode('|', array_filter($filters)) ?: null,
            'row_count'     => $exporter->rowCount,
            'generated_by'  => auth()->id(),
        ]);

        return response()->json([
            'success'      => true,
            'message'      => 'Export generated successfully.',
            'download_url' => asset($path),
            'filename'     => $filename,
        ]);
    }

    /**
     * List stored subscription export files (AJAX)
     */
    public function exportList()
    {
        $exports = ExportLog::where('type', 'subscription')
            ->with('generatedBy:id,name')
            ->latest()
            ->take(30)
            ->get()
            ->map(fn($e) => [
                'id'            => $e->id,
                'filename'      => $e->filename,
                'filter_status' => $e->filter_status ?? 'All',
                'row_count'     => $e->row_count,
                'file_size'     => $e->file_size,
                'generated_by'  => $e->generatedBy->name ?? '-',
                'created_at'    => $e->created_at->format('d M Y, h:i A'),
                'download_url'  => $e->download_url,
                'exists'        => file_exists(public_path($e->path)),
            ]);

        return response()->json(['success' => true, 'exports' => $exports]);
    }

    /**
     * Delete a stored subscription export file
     */
    public function exportDelete(ExportLog $export)
    {
        $full = public_path($export->path);
        if (file_exists($full)) unlink($full);
        $export->delete();
        return response()->json(['success' => true]);
    }

    /**
     * Display a listing of subscriptions
     */
    public function show(UserSubscription $subscription)
    {
        $subscription->load(['user', 'membershipPlan', 'location']);

        $today = now();
        $startOfMonth = $today->copy()->startOfMonth();
        $endOfMonth   = $today->copy()->endOfMonth();

        $monthDeliveries = $subscription->deliveryLogs()
            ->whereBetween('delivery_date', [$startOfMonth, $endOfMonth])
            ->get()
            ->keyBy(fn($item) => $item->delivery_date->format('Y-m-d'));

        return view('admin.subscriptions.show', compact('subscription', 'monthDeliveries'));
    }

    /**
     * Update subscription status
     */
    public function updateStatus(Request $request, UserSubscription $subscription)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,active,paused,cancelled,expired',
        ]);

        $subscription->update(['status' => $validated['status']]);

        return redirect()->route('admin.subscriptions.show', $subscription)
            ->with('success', 'Subscription status updated successfully!');
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus(Request $request, UserSubscription $subscription)
    {
        $validated = $request->validate([
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'transaction_id' => 'nullable|string|max:255',
            'amount_paid' => 'nullable|numeric|min:0',
        ]);

        $subscription->update($validated);

        return redirect()->route('admin.subscriptions.show', $subscription)
            ->with('success', 'Payment status updated successfully!');
    }

    /**
     * Add notes to subscription
     */
    public function addNote(Request $request, UserSubscription $subscription)
    {
        $validated = $request->validate([
            'notes' => 'required|string|max:1000',
        ]);

        $existingNotes = $subscription->notes ? $subscription->notes . "\n\n" : '';
        $newNote = "[" . now()->format('Y-m-d H:i:s') . "] " . $validated['notes'];
        
        $subscription->update([
            'notes' => $existingNotes . $newNote
        ]);

        return redirect()->route('admin.subscriptions.show', $subscription)
            ->with('success', 'Note added successfully!');
    }
}
