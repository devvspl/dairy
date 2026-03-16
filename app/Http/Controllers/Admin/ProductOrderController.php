<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductOrder;
use Illuminate\Http\Request;

class ProductOrderController extends Controller
{
    public function index()
    {
        $orders = ProductOrder::with('user')
            ->latest()
            ->paginate(25);

        return view('admin.product-orders.index', compact('orders'));
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
}
