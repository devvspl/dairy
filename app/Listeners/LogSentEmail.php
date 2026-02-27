<?php

namespace App\Listeners;

use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Log;

class LogSentEmail
{
    /**
     * Handle the event.
     */
    public function handle(MessageSent $event): void
    {
        $message = $event->message;
        
        Log::info('Email sent successfully', [
            'to' => collect($message->getTo())->keys()->toArray(),
            'subject' => $message->getSubject(),
            'from' => collect($message->getFrom())->keys()->toArray(),
            'timestamp' => now()->toDateTimeString()
        ]);
    }
}
