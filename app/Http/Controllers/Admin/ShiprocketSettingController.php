<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShiprocketSetting;
use App\Services\ShiprocketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ShiprocketSettingController extends Controller
{
    public function index()
    {
        $settings = ShiprocketSetting::instance();
        return view('admin.settings.shiprocket', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'pickup_location' => 'nullable|string|max:100',
            'default_city'    => 'nullable|string|max:100',
            'default_state'   => 'nullable|string|max:100',
            'default_pincode' => 'nullable|string|max:10',
            'pkg_length'      => 'nullable|numeric|min:1',
            'pkg_breadth'     => 'nullable|numeric|min:1',
            'pkg_height'      => 'nullable|numeric|min:1',
            'pkg_weight'      => 'nullable|numeric|min:0.1',
        ]);

        ShiprocketSetting::instance()->update([
            'enabled'         => $request->boolean('enabled'),
            'pickup_location' => $request->pickup_location ?? 'Primary',
            'default_city'    => $request->default_city,
            'default_state'   => $request->default_state,
            'default_pincode' => $request->default_pincode,
            'pkg_length'      => $request->pkg_length ?? 10,
            'pkg_breadth'     => $request->pkg_breadth ?? 10,
            'pkg_height'      => $request->pkg_height ?? 10,
            'pkg_weight'      => $request->pkg_weight ?? 0.5,
        ]);

        app(ShiprocketService::class)->refreshToken();

        return redirect()->back()->with('success', 'Shiprocket settings saved successfully.');
    }

    public function testConnection()
    {
        $email    = config('services.shiprocket.email');
        $password = config('services.shiprocket.password');

        if (!$email || !$password) {
            return response()->json([
                'success' => false,
                'message' => 'SHIPROCKET_EMAIL and SHIPROCKET_PASSWORD are not set in .env',
            ]);
        }

        app(ShiprocketService::class)->refreshToken();

        $response = Http::post('https://apiv2.shiprocket.in/v1/external/auth/login', [
            'email'    => $email,
            'password' => $password,
        ]);

        if ($response->successful() && $response->json('token')) {
            return response()->json(['success' => true, 'message' => 'Connection successful. Credentials are valid.']);
        }

        return response()->json([
            'success' => false,
            'message' => $response->json('message') ?? 'Authentication failed. Check your .env credentials.',
        ]);
    }
}
