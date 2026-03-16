<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ProductOrdersExport;
use App\Http\Controllers\Controller;
use App\Models\ExportLog;
use App\Models\ProductOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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

        // Ensure directory exists on public disk
        Storage::disk('public')->makeDirectory('exports/product-orders');

        Excel::store($exporter, $path, 'public');

        if (!Storage::disk('public')->exists($path)) {
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
            'download_url' => Storage::disk('public')->url($path),
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
                'exists'        => Storage::disk('public')->exists($e->path),
            ]);

        return response()->json(['success' => true, 'exports' => $exports]);
    }

    public function exportDelete(ExportLog $export)
    {
        abort_if($export->type !== 'product_orders', 403);
        Storage::disk('public')->delete($export->path);
        $export->delete();
        return response()->json(['success' => true]);
    }
}
