<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\ExchangeRate;

class SyncExchangeRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-exchange-rates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync USD to GBP and EUR exchange rates and store in the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $apiKey = config('services.exchange_rate_api.key');
        $baseUrl = config('services.exchange_rate_api.base_url');
        if (!$apiKey) {
            $this->error('Exchange Rate API key is not set.');
            return 1;
        }
        $url = "{$baseUrl}/{$apiKey}/latest/USD";
        $response = Http::get($url);
        if (!$response->successful()) {
            $this->error("Failed to fetch exchange rates. Status: {$response->status()}");
            return 1;
        }
        $data = $response->json();
        $rates = $data['conversion_rates'] ?? [];
        foreach (['GBP', 'EUR'] as $currency) {
            if (isset($rates[$currency])) {
                ExchangeRate::updateOrCreate(
                    ['currency' => $currency],
                    ['rate' => $rates[$currency]]
                );
            }
        }
        $this->info('Exchange rates synced successfully.');
    }
}
