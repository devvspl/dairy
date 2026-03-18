<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ProductOrdersExport;
use App\Http\Controllers\Controller;
use App\Models\ExportLog;
use App\Models\ProductOrder;
use App\Services\ShiprocketService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ProductOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductOrder::latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('product_id')) {
            $product = \App\Models\Product::find($request->product_id);
            if ($product) {
                $query->where('items', 'like', '%"id":' . $product->id . '%');
            }
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q
                ->where('customer_name',  'like', "%{$s}%")
                ->orWhere('customer_phone', 'like', "%{$s}%")
                ->orWhere('customer_email', 'like', "%{$s}%")
                ->orWhere('order_id',       'like', "%{$s}%")
            );
        }

        $orders = $query->paginate(25)->withQueryString();

        $stats = [
            'total'   => ProductOrder::count(),
            'success' => ProductOrder::where('status', 'success')->count(),
            'pending' => ProductOrder::where('status', 'pending')->count(),
            'revenue' => ProductOrder::where('status', 'success')->sum('amount'),
        ];

        $products = \App\Models\Product::orderBy('name')->get(['id', 'name']);

        return view('admin.product-orders.index', compact('orders', 'stats', 'products'));
    }

    public function show(ProductOrder $productOrder)
    {
        $productOrder->load('user');
        return view('admin.product-orders.show', compact('productOrder'));
    }

    public function updateStatus(Request $request, ProductOrder $productOrder)
    {
        $request->validate(['status' => 'required|in:pending,success,failed,cancelled']);
        $productOrder->update(['status' => $request->status]);
        return redirect()->back()->with('success', 'Order status updated successfully.');
    }

    public function export(Request $request)
    {
        $filters  = $request->only(['status', 'product_id', 'date_from', 'date_to', 'search']);
        $exporter = new ProductOrdersExport($filters);

        $filename = 'product-orders-' . now()->format('Y-m-d-His') . '.xlsx';
        $path     = 'exports/product-orders/' . $filename;

        Excel::store($exporter, $path, 'public_folder');

        if (!file_exists(public_path($path))) {
            return response()->json(['success' => false, 'message' => 'Export failed to save file.'], 500);
        }

        $filterLabel = collect([
            $filters['status']     ?? null,
            isset($filters['product_id']) ? 'product:' . $filters['product_id'] : null,
            $filters['date_from']  ?? null,
            $filters['date_to']    ?? null,
            !empty($filters['search']) ? 'search:' . $filters['search'] : null,
        ])->filter()->implode(' | ') ?: 'All';

        ExportLog::create([
            'type'          => 'product_orders',
            'filename'      => $filename,
            'path'          => $path,
            'filter_status' => $filterLabel,
            'row_count'     => $exporter->rowCount,
            'generated_by'  => auth()->id(),
        ]);

        return response()->json([
            'success'      => true,
            'download_url' => asset($path),
            'filename'     => $filename,
        ]);
    }

    public function exportList()
    {
        $exports = ExportLog::where('type', 'product_orders')
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

    public function exportDelete(ExportLog $export)
    {
        abort_if($export->type !== 'product_orders', 403);
        $full = public_path($export->path);
        if (file_exists($full)) unlink($full);
        $export->delete();
        return response()->json(['success' => true]);
    }

    // ─── Shiprocket ───────────────────────────────────────────────────────────

    public function shiprocketAssign(Request $request, ProductOrder $productOrder)
    {
        $shiprocket = app(ShiprocketService::class);

        if (!$shiprocket->isEnabled()) {
            return response()->json(['success' => false, 'message' => 'Shiprocket is disabled. Enable it in Settings.']);
        }

        if ($productOrder->isShiprocketAssigned()) {
            return response()->json(['success' => false, 'message' => 'Order already assigned to Shiprocket.']);
        }

        $result = $shiprocket->createOrder($productOrder);

        if ($result['success']) {
            $productOrder->update([
                'shiprocket_order_id'    => $result['order_id'],
                'shiprocket_shipment_id' => $result['shipment_id'],
                'shiprocket_awb'         => $result['awb_code'],
                'shiprocket_courier'     => $result['courier'],
                'shiprocket_status'      => $result['status'] ?? 'NEW',
                'shiprocket_assigned_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Order successfully assigned to Shiprocket.',
                'data'    => [
                    'shiprocket_order_id'    => $productOrder->shiprocket_order_id,
                    'shiprocket_shipment_id' => $productOrder->shiprocket_shipment_id,
                    'shiprocket_awb'         => $productOrder->shiprocket_awb,
                    'shiprocket_courier'     => $productOrder->shiprocket_courier,
                    'shiprocket_status'      => $productOrder->shiprocket_status,
                    'shiprocket_assigned_at' => $productOrder->shiprocket_assigned_at?->format('d M Y, h:i A'),
                ],
            ]);
        }

        return response()->json(['success' => false, 'message' => $result['message']]);
    }

    public function shiprocketTrack(ProductOrder $productOrder)
    {
        if (!$productOrder->shiprocket_awb) {
            return response()->json(['success' => false, 'message' => 'No AWB code found for this order.']);
        }

        $result = app(ShiprocketService::class)->trackOrder($productOrder->shiprocket_awb);

        if ($result['success']) {
            $tracking = $result['data']['tracking_data'] ?? $result['data'];
            $status   = data_get($tracking, 'shipment_track.0.current_status')
                     ?? data_get($tracking, 'track_status', 'Unknown');

            $productOrder->update(['shiprocket_status' => $status]);

            return response()->json(['success' => true, 'status' => $status, 'data' => $tracking]);
        }

        return response()->json(['success' => false, 'message' => $result['message']]);
    }

    public function shiprocketBulkAssign(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'integer|exists:product_orders,id']);

        $shiprocket = app(ShiprocketService::class);

        if (!$shiprocket->isEnabled()) {
            return response()->json(['success' => false, 'message' => 'Shiprocket is disabled. Enable it in Settings.']);
        }

        $orders  = ProductOrder::whereIn('id', $request->ids)
                               ->whereNull('shiprocket_order_id')
                               ->get();

        $results = ['success' => [], 'failed' => []];

        foreach ($orders as $order) {
            $result = $shiprocket->createOrder($order);
            if ($result['success']) {
                $order->update([
                    'shiprocket_order_id'    => $result['order_id'],
                    'shiprocket_shipment_id' => $result['shipment_id'],
                    'shiprocket_awb'         => $result['awb_code'],
                    'shiprocket_courier'     => $result['courier'],
                    'shiprocket_status'      => $result['status'] ?? 'NEW',
                    'shiprocket_assigned_at' => now(),
                ]);
                $results['success'][] = $order->order_id;
            } else {
                $results['failed'][] = ['order_id' => $order->order_id, 'reason' => $result['message']];
            }
        }

        $skipped = count($request->ids) - $orders->count();

        return response()->json([
            'success'        => true,
            'assigned_count' => count($results['success']),
            'failed_count'   => count($results['failed']),
            'skipped_count'  => $skipped,
            'assigned'       => $results['success'],
            'failed'         => $results['failed'],
        ]);
    }

    public function shiprocketCancel(ProductOrder $productOrder)
    {
        if (!$productOrder->shiprocket_order_id) {
            return response()->json(['success' => false, 'message' => 'No Shiprocket order found.']);
        }

        $result = app(ShiprocketService::class)->cancelOrder($productOrder->shiprocket_order_id);

        if ($result['success']) {
            $productOrder->update(['shiprocket_status' => 'CANCELLED']);
            return response()->json(['success' => true, 'message' => 'Shiprocket order cancelled.']);
        }

        return response()->json(['success' => false, 'message' => $result['message']]);
    }
}
