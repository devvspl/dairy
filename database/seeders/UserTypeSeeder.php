<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Set the first user as Admin
        \App\Models\User::where('id', 1)->update(['user_type' => 'Admin']);
        
        // Set all other users as Members
        \App\Models\User::where('id', '>', 1)->update(['user_type' => 'Member']);
        
        $this->command->info('User types updated successfully!');
        $this->command->info('User ID 1 is now Admin');
        $this->command->info('All other users are Members');
    }
}
