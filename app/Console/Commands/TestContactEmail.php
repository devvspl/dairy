<?php

namespace App\Console\Commands;

use App\Mail\ContactInquiryMail;
use App\Models\ContactInquiry;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestContactEmail extends Command
{
    protected $signature = 'email:test-contact {--admin : Send admin notification}';
    protected $description = 'Test contact inquiry email';

    public function handle()
    {
        $this->info('Testing contact email...');

        $inquiry = ContactInquiry::latest()->first();

        if (!$inquiry) {
            $this->error('No inquiries found. Submit a contact form first.');
            return 1;
        }

        $isAdmin = $this->option('admin');
        
        try {
            if ($isAdmin) {
                $adminEmail = env('MAIL_ADMIN_ADDRESS', env('MAIL_FROM_ADDRESS'));
                $this->info("Sending admin email to: {$adminEmail}");
                Mail::to($adminEmail)->send(new ContactInquiryMail($inquiry, true));
                $this->info('✓ Admin email sent!');
            } else {
                $this->info("Sending customer email to: {$inquiry->email}");
                Mail::to($inquiry->email)->send(new ContactInquiryMail($inquiry, false));
                $this->info('✓ Customer email sent!');
            }
            
            return 0;
        } catch (\Exception $e) {
            $this->error('Failed: ' . $e->getMessage());
            $this->error($e->getTraceAsString());
            return 1;
        }
    }
}
