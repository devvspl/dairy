<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductOrder;

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
        return view('admin.product-orders.show', compact('productOrder'));
    }
}
