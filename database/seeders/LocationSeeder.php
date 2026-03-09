<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        Location::create([
            'slug' => 'ace-divino-sector-1-greater-noida-west',
            'name' => 'ACE Divino',
            'area' => 'Greater Noida West',
            'sector' => '1',
            'city' => 'Noida',
            'title' => 'Bottle Milk Delivery in Sector 1, Greater Noida West',
            'description' => 'ACE Divino (a building/society) located in Sector 1, Greater Noida West.',
            'building_name' => 'ACE Divino',
            'building_type' => 'Society',
            'delivery_timing' => '5:30 AM – 8:30 AM',
            'delivery_point' => 'Flat / gate / guard as per rule',
            'handling_info' => 'Milk delivery here works on a society-first route. That means delivery is planned around ACE Divino entry rules (gate/guard) and then executed in a fixed morning sequence.',
            'address' => 'ACE Divino, Sector 1, Greater Noida West',
            'map_embed_url' => 'https://www.google.com/maps?q=ACE%20Divino%20Sector%201%20Greater%20Noida%20West&output=embed',
            'hero_badges' => [
                ['icon' => 'fa-solid fa-clock', 'text' => 'Morning Delivery Slot'],
                ['icon' => 'fa-solid fa-bottle-water', 'text' => 'Sealed Bottle Packing'],
                ['icon' => 'fa-solid fa-shield-heart', 'text' => 'Hygienic Handling'],
                ['icon' => 'fa-solid fa-route', 'text' => 'Route-Based Delivery'],
            ],
            'route_steps' => [
                ['number' => '1', 'title' => 'Route planning', 'description' => 'Sector 1 route is planned first, then society sequence is followed for smooth delivery.'],
                ['number' => '2', 'title' => 'Society entry rules', 'description' => 'If entry is restricted, delivery is done at gate/guard point as per your instructions.'],
                ['number' => '3', 'title' => 'Sealed bottle delivery', 'description' => 'Milk is delivered in sealed bottles for hygienic handling and daily household use.'],
                ['number' => '4', 'title' => 'Daily consistency', 'description' => 'Delivery is kept within the morning window; exact time may vary slightly by sequence.'],
            ],
            'highlights' => [
                ['icon' => 'fa-solid fa-bottle-water', 'title' => 'Sealed bottle packing', 'description' => 'Sealed bottles for hygienic handling and clean daily usage.'],
                ['icon' => 'fa-solid fa-clock', 'title' => 'Morning window', 'description' => 'Typical morning slot 5:30 AM – 8:30 AM (route order may affect exact time).'],
                ['icon' => 'fa-solid fa-door-open', 'title' => 'Gate/guard support', 'description' => 'Society restrictions ho to gate/guard point par delivery possible.'],
                ['icon' => 'fa-solid fa-route', 'title' => 'Fixed building route', 'description' => 'ACE Divino ke liye route-based sequence follow hota hai for consistency.'],
            ],
            'mini_items' => [
                ['title' => 'Timing', 'description' => 'Typical: 5:30 AM – 8:30 AM'],
                ['title' => 'Delivery point', 'description' => 'Flat / gate / guard as per rule'],
                ['title' => 'Handling', 'description' => 'Sealed bottles, hygienic delivery'],
            ],
            'guidelines' => [
                ['icon' => 'fa-solid fa-clock', 'title' => 'Delivery timing', 'description' => 'Typical window: 5:30 AM – 8:30 AM. Exact time depends on route sequence & entry.'],
                ['icon' => 'fa-solid fa-door-open', 'title' => 'Society / gate delivery', 'description' => 'If entry restricted, delivery can be done at gate/guard point as per your instruction.'],
                ['icon' => 'fa-solid fa-location-crosshairs', 'title' => 'Address instructions', 'description' => 'Flat/tower/landmark share karna helpful hai—missed delivery avoid hoti hai.'],
                ['icon' => 'fa-solid fa-bottle-water', 'title' => 'Bottle policy', 'description' => 'Sealed bottles are provided. Return policy as per subscription terms.'],
            ],
            'coverage_areas' => [
                ['name' => 'ACE Divino', 'details' => 'Sector 1 • Greater Noida West'],
            ],
            'faqs' => [
                ['question' => 'ACE Divino me delivery timing kya hoti hai?', 'answer' => 'Typical window 5:30 AM – 8:30 AM hota hai. Exact time route sequence aur society access par depend karta hai.'],
                ['question' => 'ACE Divino ka location exactly kahan hai?', 'answer' => 'ACE Divino, Sector 1, Greater Noida West me located hai. Map Coverage section me available hai.'],
                ['question' => 'Kya sealed bottle me milk deliver hota hai?', 'answer' => 'Haan, sealed bottle packing use hoti hai for hygienic handling.'],
                ['question' => 'Agar society entry restricted ho to?', 'answer' => 'Gate/guard point par delivery possible hai, as per society rules and your instructions.'],
                ['question' => 'Is page par online booking hoti hai?', 'answer' => 'Nahi. Ye informational location page hai—coverage, timing aur delivery guidelines explain karta hai.'],
                ['question' => 'Delivery instructions kaise share karein?', 'answer' => 'Tower/flat + gate instructions call/WhatsApp par share karein so delivery point clear rahe.'],
                ['question' => 'Kya exact fixed time possible hai?', 'answer' => 'Route-based sequence ke kaaran exact fixed minute-time guarantee nahi hota. Morning window maintain hota hai.'],
                ['question' => 'Coverage confirm kaise karein?', 'answer' => 'Contact section me call/WhatsApp karke ACE Divino tower/flat details share karein.'],
            ],
            'contact_phone' => '+919876543210',
            'contact_whatsapp' => '919876543210',
            'meta_title' => 'Milk Delivery in ACE Divino, Sector 1, Greater Noida West',
            'meta_description' => 'Fresh milk delivery service for ACE Divino residents in Sector 1, Greater Noida West. Morning delivery, sealed bottles, hygienic handling.',
            'is_active' => true,
            'order' => 1,
        ]);

        Location::create([
            'slug' => 'gaur-city-sector-4-greater-noida-west',
            'name' => 'Gaur City',
            'area' => 'Greater Noida West',
            'sector' => '4',
            'city' => 'Noida',
            'title' => 'Fresh Milk Delivery in Gaur City, Sector 4',
            'description' => 'Gaur City residential complex located in Sector 4, Greater Noida West.',
            'building_name' => 'Gaur City',
            'building_type' => 'Residential Complex',
            'delivery_timing' => '6:00 AM – 8:30 AM',
            'delivery_point' => 'Tower entrance / flat door',
            'handling_info' => 'Delivery follows tower-wise route for efficient service across the large complex.',
            'address' => 'Gaur City, Sector 4, Greater Noida West',
            'map_embed_url' => 'https://www.google.com/maps?q=Gaur%20City%20Sector%204%20Greater%20Noida%20West&output=embed',
            'hero_badges' => [
                ['icon' => 'fa-solid fa-clock', 'text' => 'Early Morning Delivery'],
                ['icon' => 'fa-solid fa-bottle-water', 'text' => 'Fresh Sealed Bottles'],
                ['icon' => 'fa-solid fa-building', 'text' => 'Tower-wise Route'],
            ],
            'route_steps' => [
                ['number' => '1', 'title' => 'Tower sequence', 'description' => 'Delivery follows a fixed tower-wise sequence for consistency.'],
                ['number' => '2', 'title' => 'Morning delivery', 'description' => 'Fresh milk delivered early morning before 8:30 AM.'],
                ['number' => '3', 'title' => 'Sealed packaging', 'description' => 'All milk delivered in sealed, hygienic bottles.'],
                ['number' => '4', 'title' => 'Daily service', 'description' => 'Regular daily delivery as per your subscription.'],
            ],
            'highlights' => [
                ['icon' => 'fa-solid fa-bottle-water', 'title' => 'Fresh & Sealed', 'description' => 'Daily fresh milk in sealed bottles.'],
                ['icon' => 'fa-solid fa-clock', 'title' => 'Early Morning', 'description' => 'Delivery between 6:00 AM – 8:30 AM.'],
                ['icon' => 'fa-solid fa-building', 'title' => 'All Towers Covered', 'description' => 'Service available across all Gaur City towers.'],
            ],
            'mini_items' => [
                ['title' => 'Timing', 'description' => 'Typical: 6:00 AM – 8:30 AM'],
                ['title' => 'Delivery point', 'description' => 'Tower entrance / flat door'],
                ['title' => 'Coverage', 'description' => 'All towers in Gaur City'],
            ],
            'guidelines' => [
                ['icon' => 'fa-solid fa-clock', 'title' => 'Delivery window', 'description' => 'Morning delivery between 6:00 AM – 8:30 AM based on tower sequence.'],
                ['icon' => 'fa-solid fa-building', 'title' => 'Tower access', 'description' => 'Delivery at tower entrance or flat door as per building rules.'],
                ['icon' => 'fa-solid fa-phone', 'title' => 'Tower & flat details', 'description' => 'Please share tower number and flat details for smooth delivery.'],
            ],
            'coverage_areas' => [
                ['name' => 'Gaur City - All Towers', 'details' => 'Sector 4 • Greater Noida West'],
            ],
            'faqs' => [
                ['question' => 'Gaur City me delivery timing kya hai?', 'answer' => 'Morning delivery 6:00 AM se 8:30 AM ke beech hoti hai, tower sequence ke according.'],
                ['question' => 'Kya sabhi towers me delivery hoti hai?', 'answer' => 'Haan, Gaur City ke sabhi towers me delivery service available hai.'],
                ['question' => 'Tower number batana zaroori hai?', 'answer' => 'Haan, tower aur flat number share karna delivery ke liye helpful hai.'],
                ['question' => 'Kya subscription cancel kar sakte hain?', 'answer' => 'Haan, subscription terms ke according cancellation possible hai.'],
            ],
            'contact_phone' => '+919876543210',
            'contact_whatsapp' => '919876543210',
            'meta_title' => 'Milk Delivery in Gaur City, Sector 4, Greater Noida West',
            'meta_description' => 'Fresh milk delivery for Gaur City residents. Early morning delivery, sealed bottles, all towers covered.',
            'is_active' => true,
            'order' => 2,
        ]);
    }
}
