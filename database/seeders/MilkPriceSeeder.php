<?php

namespace Database\Seeders;

use App\Models\MilkPrice;
use Illuminate\Database\Seeder;

class MilkPriceSeeder extends Seeder
{
    public function run(): void
    {
        $prices = [
            ['milk_type' => 'cow',      'label' => 'Cow Milk (A2)',  'price_per_litre' => 70.00, 'order' => 1],
            ['milk_type' => 'buffalo',  'label' => 'Buffalo Milk',   'price_per_litre' => 80.00, 'order' => 2],
            ['milk_type' => 'toned',    'label' => 'Toned Milk',     'price_per_litre' => 55.00, 'order' => 3],
            ['milk_type' => 'full_fat', 'label' => 'Full Fat Milk',  'price_per_litre' => 75.00, 'order' => 4],
        ];

        foreach ($prices as $price) {
            MilkPrice::updateOrCreate(
                ['milk_type' => $price['milk_type']],
                array_merge($price, ['is_active' => true])
            );
        }
    }
}
