<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\LocationDeliveriesExport;
use App\Models\DeliveryLog;
use App\Models\Location;
use App\Models\PageVisit;
use App\Models\ContactInquiry;
use App\Models\Product;
use App\Models\Blog;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // Check if user is a delivery person and redirect to delivery dashboard
        if (auth()->user()->isDeliveryPerson()) {
            return redirect()->route('delivery.dashboard');
        }
        
        // Check if user is a member and redirect to member dashboard
        if (auth()->user()->isMember()) {
            return redirect()->route('member.dashboard');
        }
        
        // Admin users see the default dashboard
        return view('dashboard');
    }

    public function member()
    {
        $user = auth()->user();

        $subscriptionHistory = \App\Models\UserSubscription::with('membershipPlan', 'location')
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        $onDemandPlans = \App\Models\MembershipPlan::active()
            ->where('plan_type', 'on_demand')
            ->orderBy('order')
            ->get();

        $onDemandSubscriptions = \App\Models\UserSubscription::with('membershipPlan')
            ->where('user_id', $user->id)
            ->whereHas('membershipPlan', fn($q) => $q->where('plan_type', 'on_demand'))
            ->orderByDesc('created_at')
            ->get()
            ->keyBy('membership_plan_id');

        // Active on-demand wallet subscription (most recent active)
        // Includes both plan-based on-demand AND wallet-only (no plan) subscriptions
        $walletSubscription = \App\Models\UserSubscription::with(['membershipPlan', 'walletTransactions'])
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('membership_plan_id') // wallet-only
                  ->orWhereHas('membershipPlan', fn($q2) => $q2->where('plan_type', 'on_demand'));
            })
            ->whereNotNull('wallet_balance')
            ->latest()
            ->first();

        // Wallet calendar: transactions for current month
        $walletCalendarData = collect();
        $deliveryCalendarData = collect();
        if ($walletSubscription) {
            $today = now();
            $walletCalendarData = \App\Models\MilkWalletTransaction::where('user_subscription_id', $walletSubscription->id)
                ->whereYear('transaction_date', $today->year)
                ->whereMonth('transaction_date', $today->month)
                ->orderBy('transaction_date')
                ->get()
                ->keyBy(fn($t) => $t->transaction_date->format('Y-m-d'));

            $deliveryCalendarData = \App\Models\DeliveryLog::where('user_subscription_id', $walletSubscription->id)
                ->whereYear('delivery_date', $today->year)
                ->whereMonth('delivery_date', $today->month)
                ->get()
                ->keyBy(fn($d) => $d->delivery_date->format('Y-m-d'));
        }

        $savedAddresses = $user->deliveryAddresses()->with('location')->get();
        $milkPrices     = \App\Models\MilkPrice::active()->ordered()->get();

        return view('member-dashboard', compact(
            'subscriptionHistory', 'onDemandPlans', 'onDemandSubscriptions',
            'walletSubscription', 'walletCalendarData', 'deliveryCalendarData',
            'savedAddresses', 'milkPrices'
        ));
    }

    public function delivery()
    {
        $user = auth()->user();
        $user->load('locations');

        // Today's stats per assigned location
        $locationStats = $user->locations->map(function ($location) {
            $base = DeliveryLog::whereHas('subscription', fn($q) => $q->where('location_id', $location->id))
                ->today();

            return [
                'location'  => $location,
                'total'     => (clone $base)->count(),
                'delivered' => (clone $base)->where('status', 'delivered')->count(),
                'pending'   => (clone $base)->where('status', 'pending')->count(),
                'quantity'  => (clone $base)->sum('quantity_delivered'),
            ];
        });

        return view('delivery-dashboard', compact('user', 'locationStats'));
    }

    public function deliveryLocation(Request $request, Location $location)
    {
        $user = auth()->user();

        if (!$user->locations->contains($location->id)) {
            abort(403, 'You are not assigned to this location.');
        }

        $date   = $request->get('date', now()->format('Y-m-d'));
        $search = $request->get('search', '');

        $query = DeliveryLog::with(['subscription.user', 'subscription.membershipPlan'])
            ->whereHas('subscription', fn($q) => $q
                ->where('location_id', $location->id)
                ->where(function($q2) use ($date) {
                    // Only show active deliveries — exclude paused/stopped on the requested date
                    $q2->where('delivery_status', 'active')
                       ->orWhere('start_date', '>', $date); // not yet started
                })
            )
            ->whereDate('delivery_date', $date)
            ->orderBy('status');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($search) {
            $query->whereHas('subscription.user', fn($q) => $q
                ->where('name', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")
            );
        }

        $deliveries = $query->paginate(50)->withQueryString();

        $statsBase = DeliveryLog::whereHas('subscription', fn($q) => $q
                ->where('location_id', $location->id)
                ->where('delivery_status', 'active')
            )
            ->whereDate('delivery_date', $date);

        $stats = [
            'total'     => (clone $statsBase)->count(),
            'delivered' => (clone $statsBase)->where('status', 'delivered')->count(),
            'pending'   => (clone $statsBase)->where('status', 'pending')->count(),
            'skipped'   => (clone $statsBase)->where('status', 'skipped')->count(),
            'quantity'  => (clone $statsBase)->sum('quantity_delivered'),
        ];

        return view('delivery-location', compact('location', 'deliveries', 'stats', 'date'));
    }

    public function deliveryLocationExport(Request $request, Location $location)
    {
        $user = auth()->user();
        if (!$user->locations->contains($location->id)) {
            abort(403);
        }

        $date     = $request->get('date', now()->format('Y-m-d'));
        $status   = (string) $request->get('status', '');
        $exporter = new LocationDeliveriesExport($location, $date, $status);

        $filename = $location->name . '-deliveries-' . $date . '-' . now()->format('His') . '.xlsx';
        $path     = 'exports/location-deliveries/' . $filename;

        \Maatwebsite\Excel\Facades\Excel::store($exporter, $path, 'public_folder');

        \App\Models\ExportLog::create([
            'type'          => 'location_delivery_' . $location->id,
            'filename'      => $filename,
            'path'          => $path,
            'filter_status' => trim(($status ?: 'All') . ' | ' . $date),
            'row_count'     => $exporter->rowCount,
            'generated_by'  => $user->id,
        ]);

        return response()->json([
            'success'      => true,
            'download_url' => asset($path),
            'filename'     => $filename,
        ]);
    }

    public function deliveryLocationExportList(Location $location)
    {
        $user = auth()->user();
        if (!$user->locations->contains($location->id)) {
            abort(403);
        }

        $exports = \App\Models\ExportLog::where('type', 'location_delivery_' . $location->id)
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

    public function deliveryLocationExportDelete(\App\Models\ExportLog $export)
    {
        $user = auth()->user();
        // Only allow deleting location_delivery_* types
        if (!str_starts_with($export->type, 'location_delivery_')) {
            abort(403);
        }
        \Illuminate\Support\Facades\Storage::disk('public_folder')->delete($export->path);
        $export->delete();
        return response()->json(['success' => true]);
    }

    public function deliveryUpdateStatus(Request $request, Location $location, DeliveryLog $delivery)
    {
        $user = auth()->user();

        if (!$user->locations->contains($location->id)) {
            abort(403);
        }

        $validated = $request->validate([
            'status'             => 'required|in:pending,delivered,skipped,failed',
            'quantity_delivered' => 'nullable|numeric|min:0',
            'delivery_time'      => 'nullable|string',
            'notes'              => 'nullable|string|max:500',
        ]);

        $deliveryTime = null;
        if (!empty($validated['delivery_time'])) {
            try {
                $deliveryTime = \Carbon\Carbon::parse($validated['delivery_time'])->format('H:i');
            } catch (\Exception $e) {
                $deliveryTime = $delivery->delivery_time;
            }
        } else {
            $deliveryTime = now()->format('H:i');
        }

        // Default note based on status if no custom note provided
        $defaultNotes = [
            'delivered' => 'Delivered successfully.',
            'skipped'   => 'Delivery skipped.',
            'failed'    => 'Delivery failed.',
            'pending'   => 'Marked as pending.',
        ];
        $notes = !empty($validated['notes'])
            ? $validated['notes']
            : ($defaultNotes[$validated['status']] ?? null);

        $delivery->update([
            'status'             => $validated['status'],
            'quantity_delivered' => $validated['quantity_delivered'] ?? $delivery->quantity_delivered,
            'delivery_time'      => $deliveryTime,
            'notes'              => $notes,
            'marked_by'          => $user->id,
            'marked_at'          => now(),
        ]);

        // Auto-debit wallet for on-demand subscriptions when marked delivered
        if ($validated['status'] === 'delivered') {
            $subscription = $delivery->subscription;
            if ($subscription && $subscription->isOnDemand() && $subscription->wallet_balance !== null) {
                $qty = (float) ($validated['quantity_delivered'] ?? $delivery->quantity_delivered);
                $subscription->debitWallet($qty, $delivery->delivery_date->toDateString(), $user->id);
            }
        }

        return redirect()->back()->with('success', 'Delivery updated successfully.');
    }

    public function admin()
    {
        // Visitor Statistics
        $totalVisits = PageVisit::getTotalVisits();
        $uniqueVisitors = PageVisit::getUniqueVisitors();
        $todayVisits = PageVisit::getTodayVisits();
        $todayUniqueVisitors = PageVisit::getTodayUniqueVisitors();

        $last7Days = PageVisit::getVisitsByDateRange(
            now()->subDays(6)->startOfDay(),
            now()->endOfDay()
        );

        $mostVisitedPages    = PageVisit::getMostVisitedPages(5);
        $deviceStats         = PageVisit::getDeviceStats();
        $browserStats        = PageVisit::getBrowserStats();

        $totalInquiries = ContactInquiry::count();
        $newInquiries   = ContactInquiry::where('status', 'new')->count();
        $totalProducts  = Product::count();
        $totalBlogs     = Blog::count();
        $totalUsers     = User::count();

        // Membership stats
        $totalSubscriptions  = \App\Models\UserSubscription::count();
        $activeSubscriptions = \App\Models\UserSubscription::where('status', 'active')->count();
        $expiredSubscriptions= \App\Models\UserSubscription::where('status', 'expired')->count();
        $membershipRevenue   = \App\Models\UserSubscription::where('payment_status', 'paid')->sum('amount_paid');
        $recentSubscriptions = \App\Models\UserSubscription::with('user', 'membershipPlan')
            ->latest()->take(5)->get();

        // Product order stats
        $totalProductOrders   = \App\Models\ProductOrder::count();
        $pendingProductOrders = \App\Models\ProductOrder::where('status', 'pending')->count();
        $successProductOrders = \App\Models\ProductOrder::where('status', 'success')->count();
        $productOrderRevenue  = \App\Models\ProductOrder::where('status', 'success')->sum('amount');
        $recentProductOrders  = \App\Models\ProductOrder::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalVisits', 'uniqueVisitors', 'todayVisits', 'todayUniqueVisitors',
            'last7Days', 'mostVisitedPages', 'deviceStats', 'browserStats',
            'totalInquiries', 'newInquiries', 'totalProducts', 'totalBlogs', 'totalUsers',
            'totalSubscriptions', 'activeSubscriptions', 'expiredSubscriptions', 'membershipRevenue', 'recentSubscriptions',
            'totalProductOrders', 'pendingProductOrders', 'successProductOrders', 'productOrderRevenue', 'recentProductOrders'
        ));
    }
}

