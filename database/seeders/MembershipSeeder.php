<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MembershipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membership Plans
        \App\Models\MembershipPlan::create([
            'name' => 'Basic Membership',
            'slug' => 'basic-membership',
            'price' => 199,
            'duration' => 'month',
            'badge' => 'Starter',
            'icon' => 'fa-seedling',
            'description' => 'Perfect for first-time members who want simple benefits.',
            'features' => [
                'Member-only offers',
                'Early access to deals',
                'Basic support',
            ],
            'order' => 1,
            'is_featured' => false,
            'is_active' => true,
        ]);

        \App\Models\MembershipPlan::create([
            'name' => 'Premium Membership',
            'slug' => 'premium-membership',
            'price' => 499,
            'duration' => 'month',
            'badge' => 'Most Popular',
            'icon' => 'fa-fire',
            'description' => 'Best value plan with priority benefits and stronger savings.',
            'features' => [
                'Bigger member discounts',
                'Priority support',
                'Free shipping (conditions)',
                'Exclusive drops',
            ],
            'order' => 2,
            'is_featured' => true,
            'is_active' => true,
        ]);

        \App\Models\MembershipPlan::create([
            'name' => 'Elite Membership',
            'slug' => 'elite-membership',
            'price' => 999,
            'duration' => 'month',
            'badge' => 'Elite',
            'icon' => 'fa-gem',
            'description' => 'For frequent buyers who want the maximum premium experience.',
            'features' => [
                'Highest discounts',
                'Dedicated support',
                'Surprise gifts',
                'VIP early access',
            ],
            'order' => 3,
            'is_featured' => false,
            'is_active' => true,
        ]);

        // Membership Benefits
        \App\Models\MembershipBenefit::create([
            'title' => 'Member-only pricing',
            'description' => 'Special offers available only for members on selected products.',
            'icon' => 'fa-tags',
            'order' => 1,
            'is_active' => true,
        ]);

        \App\Models\MembershipBenefit::create([
            'title' => 'Priority support',
            'description' => 'Faster resolutions and smoother assistance for members.',
            'icon' => 'fa-headset',
            'order' => 2,
            'is_active' => true,
        ]);

        \App\Models\MembershipBenefit::create([
            'title' => 'Faster dispatch',
            'description' => 'Members get quick processing during peak demand windows.',
            'icon' => 'fa-truck-fast',
            'order' => 3,
            'is_active' => true,
        ]);

        // Membership Steps
        \App\Models\MembershipStep::create([
            'step_number' => 1,
            'title' => 'Choose a plan',
            'description' => 'Select a plan that matches your buying pattern.',
            'order' => 1,
            'is_active' => true,
        ]);

        \App\Models\MembershipStep::create([
            'step_number' => 2,
            'title' => 'Submit details',
            'description' => 'Share your name and contact details for activation.',
            'order' => 2,
            'is_active' => true,
        ]);

        \App\Models\MembershipStep::create([
            'step_number' => 3,
            'title' => 'Activation',
            'description' => 'We confirm and activate the membership quickly.',
            'order' => 3,
            'is_active' => true,
        ]);

        \App\Models\MembershipStep::create([
            'step_number' => 4,
            'title' => 'Enjoy benefits',
            'description' => 'Start using discounts and priority support instantly.',
            'order' => 4,
            'is_active' => true,
        ]);

        // Membership FAQs
        \App\Models\MembershipFaq::create([
            'question' => 'When does my membership start?',
            'answer' => 'After payment/confirmation, membership is activated as per plan terms.',
            'order' => 1,
            'is_active' => true,
        ]);

        \App\Models\MembershipFaq::create([
            'question' => 'Can I upgrade later?',
            'answer' => 'Yes, you can upgrade anytime to access higher benefits.',
            'order' => 2,
            'is_active' => true,
        ]);

        \App\Models\MembershipFaq::create([
            'question' => 'Is free shipping included?',
            'answer' => 'Free shipping depends on plan and order conditions. Update terms as needed.',
            'order' => 3,
            'is_active' => true,
        ]);

        \App\Models\MembershipFaq::create([
            'question' => 'How do I get support?',
            'answer' => 'Members get priority support through call/WhatsApp/email as per business setup.',
            'order' => 4,
            'is_active' => true,
        ]);
    }
}
