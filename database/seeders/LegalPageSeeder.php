<?php

namespace Database\Seeders;

use App\Models\LegalPage;
use Illuminate\Database\Seeder;

class LegalPageSeeder extends Seeder
{
    public function run(): void
    {
        // Privacy Policy
        LegalPage::updateOrCreate(
            ['page_key' => 'privacy-policy'],
            [
                'title' => 'Privacy Policy',
                'hero_description' => 'We are committed to protecting your privacy. This Privacy Policy explains how we collect, use, and safeguard your personal information.',
                'content' => '
                    <div class="pp-section">
                      <h2>1. Information We Collect</h2>
                      <p>
                        We may collect personal information such as your name, phone number,
                        email address, delivery address, and order details when you place
                        an order or contact us.
                      </p>
                    </div>

                    <div class="pp-section">
                      <h2>2. How We Use Your Information</h2>
                      <p>
                        Your information is used to process orders, deliver dairy products,
                        respond to inquiries, provide customer support, and improve our services.
                      </p>
                    </div>

                    <div class="pp-section">
                      <h2>3. Sharing of Information</h2>
                      <p>
                        We do not sell or rent your personal information.
                        Data may be shared only with trusted delivery and service partners
                        strictly for fulfilling your requests.
                      </p>
                    </div>

                    <div class="pp-section">
                      <h2>4. Cookies</h2>
                      <p>
                        Our website may use cookies to improve functionality and user experience.
                        You can disable cookies anytime through your browser settings.
                      </p>
                    </div>

                    <div class="pp-section">
                      <h2>5. Data Security</h2>
                      <p>
                        We take reasonable steps to protect your personal information.
                        However, no online data transmission is completely secure.
                      </p>
                    </div>

                    <div class="pp-section">
                      <h2>6. Data Retention</h2>
                      <p>
                        Information is retained only as long as required for order processing,
                        legal compliance, and customer support.
                      </p>
                    </div>

                    <div class="pp-section">
                      <h2>7. Children\'s Privacy</h2>
                      <p>
                        Our products and website are not intended for children under 13 years of age.
                        We do not knowingly collect personal data from children.
                      </p>
                    </div>

                    <div class="pp-section">
                      <h2>8. Your Rights</h2>
                      <p>
                        You may request access, correction, or deletion of your personal information
                        by contacting us directly.
                      </p>
                    </div>

                    <div class="pp-section">
                      <h2>9. Changes to This Policy</h2>
                      <p>
                        This Privacy Policy may be updated from time to time.
                        Any changes will be posted on this page with a revised date.
                      </p>
                    </div>
                ',
                'last_updated' => 'February 22, 2026',
                'contact_email' => 'support@yourdairyfarm.com',
                'contact_phone' => '+91-XXXXXXXXXX',
                'contact_address' => 'Your Dairy Farm Address, India',
                'is_active' => true,
            ]
        );

        // Terms & Conditions
        LegalPage::updateOrCreate(
            ['page_key' => 'terms-conditions'],
            [
                'title' => 'Terms & Conditions',
                'hero_description' => 'These Terms and Conditions govern how we manage user information and protect privacy on our website.',
                'content' => '
                    <div class="tc-section">
                      <h2>1. Use of Website</h2>
                      <p>
                        You agree to use this website only for lawful purposes. Any misuse, unauthorized access,
                        or activity that disrupts website operations is strictly prohibited.
                      </p>
                    </div>

                    <div class="tc-section">
                      <h2>2. Product Information</h2>
                      <p>
                        We strive to provide accurate information about our dairy products. However, product
                        availability, pricing, packaging, and descriptions may change without prior notice.
                      </p>
                    </div>

                    <div class="tc-section">
                      <h2>3. Orders & Payments</h2>
                      <p>
                        All orders placed through our website are subject to acceptance and availability.
                        Payments must be completed using approved payment methods before order confirmation.
                      </p>
                    </div>

                    <div class="tc-section">
                      <h2>4. Delivery</h2>
                      <p>
                        Delivery timelines are estimates and may vary due to location, weather conditions,
                        or unforeseen circumstances. We are not responsible for delays beyond our control.
                      </p>
                    </div>

                    <div class="tc-section">
                      <h2>5. Cancellations & Refunds</h2>
                      <p>
                        Cancellation and refund requests are handled as per our refund policy.
                        Perishable dairy products may not be eligible for cancellation once dispatched.
                      </p>
                    </div>

                    <div class="tc-section">
                      <h2>6. User Responsibilities</h2>
                      <p>
                        You are responsible for providing accurate information while placing orders.
                        We are not liable for issues arising due to incorrect delivery details or contact information.
                      </p>
                    </div>

                    <div class="tc-section">
                      <h2>7. Intellectual Property</h2>
                      <p>
                        All content on this website, including text, images, logos, and designs,
                        is the property of our dairy farm and may not be copied or reused without permission.
                      </p>
                    </div>

                    <div class="tc-section">
                      <h2>8. Limitation of Liability</h2>
                      <p>
                        We are not liable for any indirect, incidental, or consequential damages
                        resulting from the use of our website or products.
                      </p>
                    </div>

                    <div class="tc-section">
                      <h2>9. Changes to Terms</h2>
                      <p>
                        We reserve the right to update these Terms and Conditions at any time.
                        Continued use of the website implies acceptance of the revised terms.
                      </p>
                    </div>
                ',
                'last_updated' => 'February 22, 2026',
                'contact_email' => 'support@yourdairyfarm.com',
                'contact_phone' => '+91-XXXXXXXXXX',
                'contact_address' => 'Your Dairy Farm Address, India',
                'is_active' => true,
            ]
        );
    }
}
