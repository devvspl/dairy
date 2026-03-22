<?php

namespace Database\Seeders;

use App\Models\MembershipPlan;
use Illuminate\Database\Seeder;

class OnDemandMilkPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            // ── Occasion / One-Time Plans ─────────────────────────────────────
            ['name'=>'1-Day Milk Pack','slug'=>'1-day-milk-pack','plan_type'=>'on_demand','duration'=>'7_days','price'=>99,'badge'=>'One-Time','icon'=>'fa-bolt','description'=>'Need milk just for today or tomorrow? Order once for a single day — perfect for guests or a quick need.','features'=>['Single day delivery','Any milk product of your choice','Use within 7 days of purchase','No subscription needed'],'order'=>1,'is_featured'=>false,'is_active'=>true],
            ['name'=>'2-Day Milk Pack','slug'=>'2-day-milk-pack','plan_type'=>'on_demand','duration'=>'7_days','price'=>190,'badge'=>'Occasion','icon'=>'fa-cake-candles','description'=>'Planning a party or small gathering? Get fresh milk delivered on 2 days of your choice.','features'=>['2 days delivery of your choice','Any milk product of your choice','Use within 7 days of purchase','No subscription needed'],'order'=>2,'is_featured'=>false,'is_active'=>true],
            ['name'=>'3-Day Milk Pack','slug'=>'3-day-milk-pack','plan_type'=>'on_demand','duration'=>'7_days','price'=>275,'badge'=>'Event Pack','icon'=>'fa-star-and-crescent','description'=>'Festival, wedding prep, or a long weekend? Cover 3 days of fresh milk without any commitment.','features'=>['3 days delivery of your choice','Any milk product of your choice','Use within 7 days of purchase','No subscription needed'],'order'=>3,'is_featured'=>false,'is_active'=>true],
            ['name'=>'5-Day Milk Pack','slug'=>'5-day-milk-pack','plan_type'=>'on_demand','duration'=>'7_days','price'=>425,'badge'=>'Short Stay','icon'=>'fa-house-flag','description'=>'Visiting family or hosting for a few days? 5 days of on-demand milk, no strings attached.','features'=>['5 days delivery of your choice','Any milk product of your choice','Use within 7 days of purchase','No subscription needed'],'order'=>4,'is_featured'=>false,'is_active'=>true],
            // ── Regular On-Demand Plans ───────────────────────────────────────
            ['name'=>'7-Day Milk Pack','slug'=>'7-day-milk-pack','plan_type'=>'on_demand','duration'=>'7_days','price'=>595,'badge'=>'Trial','icon'=>'fa-calendar-week','description'=>'Try us out for a week. Order fresh milk any day within 7 days.','features'=>['Order any day within 7 days','Any milk product of your choice','No fixed schedule','Fresh delivery on demand'],'order'=>10,'is_featured'=>false,'is_active'=>true],
            ['name'=>'10-Day Milk Pack','slug'=>'10-day-milk-pack','plan_type'=>'on_demand','duration'=>'15_days','price'=>850,'badge'=>'Short Stay','icon'=>'fa-calendar','description'=>'Order milk on any 10 days within a 15-day window. Great for short trips or guests.','features'=>['Order any day within 15 days','Any milk product of your choice','No fixed schedule','Skip days freely'],'order'=>11,'is_featured'=>false,'is_active'=>true],
            ['name'=>'15-Day Milk Pack','slug'=>'15-day-milk-pack','plan_type'=>'on_demand','duration'=>'15_days','price'=>1190,'badge'=>'Flexible','icon'=>'fa-calendar-days','description'=>'Order fresh milk on any days you choose within 15 days. Perfect for irregular schedules.','features'=>['Order any day within 15 days','Any milk product of your choice','Skip days without losing credit','No fixed schedule'],'order'=>12,'is_featured'=>false,'is_active'=>true],
            ['name'=>'20-Day Milk Pack','slug'=>'20-day-milk-pack','plan_type'=>'on_demand','duration'=>'1_month','price'=>1600,'badge'=>'Smart Pick','icon'=>'fa-droplet','description'=>'Order milk on 20 days within a month. Ideal if you skip weekends or travel occasionally.','features'=>['Order any day within 30 days','Any milk product of your choice','Skip days without losing credit','No fixed schedule'],'order'=>13,'is_featured'=>false,'is_active'=>true],
            ['name'=>'30-Day Milk Pack','slug'=>'30-day-milk-pack','plan_type'=>'on_demand','duration'=>'1_month','price'=>2295,'badge'=>'Most Popular','icon'=>'fa-calendar-check','description'=>'Full month of flexible milk delivery. Order whenever you need it — no daily commitment.','features'=>['Order any day within 30 days','Any milk product of your choice','Skip days without losing credit','Priority delivery','No fixed schedule'],'order'=>14,'is_featured'=>true,'is_active'=>true],
            ['name'=>'45-Day Milk Pack','slug'=>'45-day-milk-pack','plan_type'=>'on_demand','duration'=>'3_months','price'=>3400,'badge'=>'Extended','icon'=>'fa-mug-hot','description'=>'Order milk on any 45 days within 3 months. Great for families who stock up.','features'=>['Order any day within 90 days','Any milk product of your choice','Skip days without losing credit','Priority delivery','No fixed schedule'],'order'=>15,'is_featured'=>false,'is_active'=>true],
            ['name'=>'60-Day Milk Pack','slug'=>'60-day-milk-pack','plan_type'=>'on_demand','duration'=>'3_months','price'=>4500,'badge'=>'Value Pack','icon'=>'fa-bottle-droplet','description'=>'Two months of on-demand milk within a 3-month window. Flexible and economical.','features'=>['Order any day within 90 days','Any milk product of your choice','Skip days without losing credit','Priority delivery','No fixed schedule'],'order'=>16,'is_featured'=>false,'is_active'=>true],
            ['name'=>'90-Day Milk Pack','slug'=>'90-day-milk-pack','plan_type'=>'on_demand','duration'=>'3_months','price'=>6375,'badge'=>'Best Value','icon'=>'fa-star','description'=>'Stock up for 3 months. Order milk on your own terms — any product, any day.','features'=>['Order any day within 90 days','Any milk product of your choice','Skip days without losing credit','Priority delivery','Dedicated support','No fixed schedule'],'order'=>17,'is_featured'=>false,'is_active'=>true],
            ['name'=>'180-Day Milk Pack','slug'=>'180-day-milk-pack','plan_type'=>'on_demand','duration'=>'6_months','price'=>11900,'badge'=>'Half Year','icon'=>'fa-award','description'=>'Six months of on-demand milk. Maximum flexibility for long-term households.','features'=>['Order any day within 180 days','Any milk product of your choice','Skip days without losing credit','Priority delivery','Dedicated support','No fixed schedule'],'order'=>18,'is_featured'=>false,'is_active'=>true],
            ['name'=>'365-Day Milk Pack','slug'=>'365-day-milk-pack','plan_type'=>'on_demand','duration'=>'1_year','price'=>22000,'badge'=>'Annual','icon'=>'fa-crown','description'=>'A full year of on-demand milk. The ultimate plan for committed dairy lovers.','features'=>['Order any day within 365 days','Any milk product of your choice','Skip days without losing credit','Priority delivery','Dedicated support','Surprise gifts','No fixed schedule'],'order'=>19,'is_featured'=>false,'is_active'=>true],
        ];

        foreach ($plans as $plan) {
            MembershipPlan::updateOrCreate(['slug' => $plan['slug']], $plan);
        }

        $this->command->info('✅ On-demand milk plans seeded (' . count($plans) . ' plans).');
    }
}
