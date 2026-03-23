<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\ProductOrder;
use Illuminate\Http\Request;

class ProductOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductOrder::where('user_id', auth()->id())->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->whereIn('status', ['success', 'failed']);
        }

        if ($request->filled('search')) {
            $query->where('order_id', 'like', '%' . $request->search . '%');
        }

        $orders = $query->paginate(15)->withQueryString();

        return view('member.product-orders', compact('orders'));
    }

    public function show(ProductOrder $productOrder)
    {
        abort_if($productOrder->user_id !== auth()->id(), 403);

        return view('member.product-order-show', compact('productOrder'));
    }
}
