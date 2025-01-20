<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendSubscriptionJob implements ShouldQueue
{
    use Queueable;

    protected $subscription;

    /**
     * Create a new job instance.
     */
    public function __construct($subscription)
    {
        $this->subscription = $subscription;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $payload = [
            'ProductName' => $this->subscription['name'],
            'Price' => $this->subscription['price'],
            'Timestamp' => now()->toDateTimeString(),
        ];

        try {
            sleep(10);
            Log::info('Sending subscription to very-slow-api.com', $payload);
            Http::post('https://very-slow-api.com/orders', $payload);
        } catch (\Exception $e) {
            Log::error('Failed to send subscription to very-slow-api.com', $payload);
            return;
        }
        
    }
}
