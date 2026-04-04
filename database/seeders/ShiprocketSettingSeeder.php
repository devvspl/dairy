<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShiprocketSettingSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('shiprocket_settings')->updateOrInsert(
            ['id' => 1],
            [
                'enabled'          => false,
                'pickup_location'  => 'Sanjay',
                'default_city'     => 'Gautam Buddha Nagar',
                'default_state'    => 'Uttar Pradesh',
                'default_pincode'  => '201318',
                'pkg_length'       => 10,
                'pkg_breadth'      => 10,
                'pkg_height'       => 10,
                'pkg_weight'       => 0.5,
                'updated_at'       => now(),
                'created_at'       => now(),
            ]
        );
    }
}
