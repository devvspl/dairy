<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\DeliveryAddress;
use Illuminate\Http\Request;

class DeliveryAddressController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'location_id' => 'nullable|exists:locations,id',
            'label'       => 'nullable|string|max:50',
            'flat_no'     => 'nullable|string|max:100',
            'address'     => 'required|string|max:500',
            'is_default'  => 'nullable|boolean',
        ]);

        $user    = auth()->user();
        $isFirst = $user->deliveryAddresses()->count() === 0;

        $addr = DeliveryAddress::create(array_merge($data, [
            'user_id'    => $user->id,
            'is_default' => $isFirst || !empty($data['is_default']),
        ]));

        if ($addr->is_default) {
            DeliveryAddress::where('user_id', $user->id)
                ->where('id', '!=', $addr->id)
                ->update(['is_default' => false]);
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'address' => $addr->load('location')]);
        }

        return redirect()->back()->with('success', 'Address saved.');
    }

    public function update(Request $request, DeliveryAddress $deliveryAddress)
    {
        abort_if($deliveryAddress->user_id !== auth()->id(), 403);

        $data = $request->validate([
            'location_id' => 'nullable|exists:locations,id',
            'label'       => 'nullable|string|max:50',
            'flat_no'     => 'nullable|string|max:100',
            'address'     => 'required|string|max:500',
            'is_default'  => 'nullable|boolean',
        ]);

        $deliveryAddress->update($data);

        if (!empty($data['is_default'])) {
            $deliveryAddress->makeDefault();
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Address updated.');
    }

    public function destroy(DeliveryAddress $deliveryAddress)
    {
        abort_if($deliveryAddress->user_id !== auth()->id(), 403);
        $deliveryAddress->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Address removed.');
    }

    public function setDefault(DeliveryAddress $deliveryAddress)
    {
        abort_if($deliveryAddress->user_id !== auth()->id(), 403);
        $deliveryAddress->makeDefault();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Default address updated.');
    }
}
