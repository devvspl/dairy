<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'name' => 'Bottle Milk',
                'slug' => 'bottle-milk',
                'description' => 'Fresh milk in bottles',
                'icon' => 'fa-bottle-water',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Flavoured Milk',
                'slug' => 'flavoured-milk',
                'description' => 'Delicious flavored milk varieties',
                'icon' => 'fa-glass-water',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Lassi',
                'slug' => 'lassi',
                'description' => 'Traditional yogurt-based drink',
                'icon' => 'fa-mug-hot',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Buttermilk (Chaas)',
                'slug' => 'buttermilk-chaas',
                'description' => 'Refreshing buttermilk',
                'icon' => 'fa-glass',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Curd (Dahi)',
                'slug' => 'curd-dahi',
                'description' => 'Fresh homemade style curd',
                'icon' => 'fa-bowl-food',
                'order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Paneer',
                'slug' => 'paneer',
                'description' => 'Fresh cottage cheese',
                'icon' => 'fa-cheese',
                'order' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'Cream',
                'slug' => 'cream',
                'description' => 'Rich dairy cream',
                'icon' => 'fa-ice-cream',
                'order' => 7,
                'is_active' => true,
            ],
        ];

        foreach ($types as $type) {
            Type::updateOrCreate(
                ['slug' => $type['slug']],
                $type
            );
        }
    }
}
