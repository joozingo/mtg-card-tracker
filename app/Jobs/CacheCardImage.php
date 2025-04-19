<?php

namespace App\Jobs;

use App\Models\Card;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CacheCardImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Card $card;
    protected string $size;

    /**
     * Create a new job instance.
     */
    public function __construct(Card $card, string $size = 'normal')
    {
        $this->card = $card;
        $this->size = $size; // e.g., 'small', 'normal', 'large'
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $scryfallUrl = match ($this->size) {
            'small' => $this->card->image_uri_small,
            'large' => $this->card->image_uri_large,
            default => $this->card->image_uri_normal,
        };

        if (!$scryfallUrl) {
            Log::warning("No Scryfall image URL found for card {$this->card->scryfall_id} size {$this->size}");
            return;
        }

        // Generate a structured path, e.g., card_images/normal/ab/abcdef12-....jpg
        $prefix = substr($this->card->scryfall_id, 0, 2);
        $filename = $this->card->scryfall_id . '.jpg'; // Assuming jpg, might need adjustment
        $relativePath = "card_images/{$this->size}/{$prefix}/{$filename}";
        $fullPath = Storage::disk('public')->path($relativePath);

        // Check if already exists (another job might have finished first)
        if (Storage::disk('public')->exists($relativePath)) {
            Log::info("Image already cached for card {$this->card->scryfall_id} size {$this->size}");
            return;
        }

        // Ensure directory exists
        Storage::disk('public')->makeDirectory(dirname($relativePath));

        try {
            $response = Http::withoutVerifying()->timeout(60)->get($scryfallUrl);

            if ($response->successful()) {
                Storage::disk('public')->put($relativePath, $response->body());
                Log::info("Successfully cached image for card {$this->card->scryfall_id} size {$this->size}");
            } else {
                Log::error("Failed to download image for card {$this->card->scryfall_id} size {$this->size}. Status: " . $response->status());
            }
        } catch (\Exception $e) {
            Log::error("Exception downloading image for card {$this->card->scryfall_id} size {$this->size}: " . $e->getMessage());
        }
    }
}
