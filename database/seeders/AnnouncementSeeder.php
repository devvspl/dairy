<?php

namespace Database\Seeders;

use App\Models\Announcement;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        $announcements = [
            [
                'title' => 'Big Sale',
                'message' => 'Big Savings Alert! Get <b>10% OFF</b> on orders above <b>₹3000</b> | Use code - <b>TBOF10</b>',
                'icon' => '🎉',
                'link' => null,
                'link_text' => null,
                'order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Salary Day Offer',
                'message' => 'Salary Day: Get <b>12% OFF</b> for Your Loved Ones | Code: <b>SAL12</b> | <b>ONLY ON APP</b>',
                'icon' => '⭐',
                'link' => null,
                'link_text' => null,
                'order' => 2,
                'is_active' => true,
            ],
        ];

        foreach ($announcements as $announcement) {
            Announcement::updateOrCreate(
                ['title' => $announcement['title']],
                $announcement
            );
        }
    }
}
