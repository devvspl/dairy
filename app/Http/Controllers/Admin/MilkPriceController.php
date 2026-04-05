<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MilkPrice;
use Illuminate\Http\Request;

class MilkPriceController extends Controller
{
    public function index()
    {
        $prices = MilkPrice::ordered()->get();
        return view('admin.milk-prices.index', compact('prices'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'milk_type'        => 'required|string|max:50|unique:milk_prices,milk_type',
            'label'            => 'required|string|max:100',
            'price_per_litre'  => 'required|numeric|min:0',
            'cutoff_time'      => 'required|date_format:H:i',
            'available_slots'  => 'required|array|min:1',
            'available_slots.*'=> 'in:morning,evening',
            'is_active'        => 'nullable|boolean',
            'order'            => 'nullable|integer',
        ]);

        MilkPrice::create(array_merge($data, ['is_active' => !empty($data['is_active'])]));

        return redirect()->route('admin.milk-prices.index')->with('success', 'Milk price added.');
    }

    public function update(Request $request, MilkPrice $milkPrice)
    {
        $data = $request->validate([
            'label'            => 'required|string|max:100',
            'price_per_litre'  => 'required|numeric|min:0',
            'cutoff_time'      => 'required|date_format:H:i',
            'available_slots'  => 'required|array|min:1',
            'available_slots.*'=> 'in:morning,evening',
            'is_active'        => 'nullable|boolean',
            'order'            => 'nullable|integer',
        ]);

        $milkPrice->update(array_merge($data, ['is_active' => !empty($data['is_active'])]));

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('admin.milk-prices.index')->with('success', 'Milk price updated.');
    }

    public function destroy(MilkPrice $milkPrice)
    {
        $milkPrice->delete();
        return redirect()->route('admin.milk-prices.index')->with('success', 'Milk price deleted.');
    }
}
