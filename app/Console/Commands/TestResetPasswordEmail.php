<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;

class TestResetPasswordEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test-reset-password {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test password reset email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email') ?? 'admin@example.com';
        
        $this->info("Attempting to send password reset email to: {$email}");
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User with email {$email} not found!");
            $this->info("Available users:");
            User::all()->each(function($u) {
                $this->line("  - {$u->email} ({$u->name})");
            });
            return 1;
        }
        
        try {
            $status = Password::sendResetLink(['email' => $email]);
            
            if ($status === Password::RESET_LINK_SENT) {
                $this->info("✓ Password reset email sent successfully to {$user->name} ({$email})");
                $this->info("Check your email inbox!");
                Log::info("Test password reset email sent to: {$email}");
            } else {
                $this->error("✗ Failed to send password reset email");
                $this->error("Status: {$status}");
            }
        } catch (\Exception $e) {
            $this->error("✗ Error sending email: " . $e->getMessage());
            Log::error("Password reset email error: " . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}
