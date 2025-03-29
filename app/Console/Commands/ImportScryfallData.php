<?php

namespace App\Console\Commands;

use App\Models\Card;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ImportScryfallData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-scryfall-data {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Scryfall bulk data into the cards table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = $this->argument('file');
        $chunkSize = 1000; // Default chunk size

        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return 1;
        }

        $this->info("Importing Scryfall data from {$filePath}");
        $this->info("This may take a while depending on the file size...");

        // Begin a transaction
        DB::beginTransaction();

        try {
            // Read the file
            $handle = fopen($filePath, 'r');

            if (!$handle) {
                throw new \Exception("Could not open file: {$filePath}");
            }

            // Initialize counters
            $processedCount = 0;
            $currentChunk = [];

            // Create a progress bar
            $fileSize = filesize($filePath);
            $bar = $this->output->createProgressBar($fileSize);
            $bar->start();

            while (!feof($handle)) {
                $line = fgets($handle);

                if (!$line)
                    continue;

                // Update progress
                $bar->advance(strlen($line));

                // Skip non-JSON lines (like brackets)
                if (trim($line) == '[' || trim($line) == ']')
                    continue;

                // Remove trailing comma if present
                if (substr(rtrim($line), -1) == ',') {
                    $line = substr(rtrim($line), 0, -1);
                }

                // Decode the JSON
                $cardData = json_decode($line, true);

                if (!$cardData)
                    continue;

                // Only process actual cards (skip tokens, etc.)
                if (!isset($cardData['id']) || !isset($cardData['name']))
                    continue;

                // Process image URIs
                $imageUris = $cardData['image_uris'] ?? null;
                $images = [
                    'small' => null,
                    'normal' => null,
                    'large' => null
                ];

                if ($imageUris) {
                    $images['small'] = $imageUris['small'] ?? null;
                    $images['normal'] = $imageUris['normal'] ?? null;
                    $images['large'] = $imageUris['large'] ?? null;
                }

                // Process prices
                $prices = $cardData['prices'] ?? [];

                // Format the card data
                $formattedCard = [
                    'scryfall_id' => $cardData['id'] ?? null,
                    'name' => $cardData['name'] ?? null,
                    'oracle_id' => $cardData['oracle_id'] ?? null,
                    'oracle_text' => $cardData['oracle_text'] ?? null,
                    'mana_cost' => $cardData['mana_cost'] ?? null,
                    'type_line' => $cardData['type_line'] ?? null,
                    'set' => $cardData['set'] ?? null,
                    'set_name' => $cardData['set_name'] ?? null,
                    'rarity' => $cardData['rarity'] ?? null,
                    'cmc' => $cardData['cmc'] ?? null,
                    'power' => $cardData['power'] ?? null,
                    'toughness' => $cardData['toughness'] ?? null,
                    'loyalty' => $cardData['loyalty'] ?? null,
                    'colors' => isset($cardData['colors']) ? json_encode($cardData['colors']) : null,
                    'color_identity' => isset($cardData['color_identity']) ? json_encode($cardData['color_identity']) : null,
                    'layout' => $cardData['layout'] ?? null,
                    'reserved' => $cardData['reserved'] ?? false,
                    'artist' => $cardData['artist'] ?? null,
                    'collector_number' => $cardData['collector_number'] ?? null,
                    'image_uri_small' => $images['small'],
                    'image_uri_normal' => $images['normal'],
                    'image_uri_large' => $images['large'],
                    'price_usd' => isset($prices['usd']) ? (float) $prices['usd'] : null,
                    'price_eur' => isset($prices['eur']) ? (float) $prices['eur'] : null,
                    'keywords' => isset($cardData['keywords']) ? json_encode($cardData['keywords']) : null,
                    'legalities' => isset($cardData['legalities']) ? json_encode($cardData['legalities']) : null,
                ];

                $currentChunk[] = $formattedCard;

                // Process in chunks
                if (count($currentChunk) >= $chunkSize) {
                    $this->insertCards($currentChunk);
                    $processedCount += count($currentChunk);
                    $currentChunk = [];

                    $this->info("\nProcessed {$processedCount} cards so far");
                }
            }

            // Insert any remaining cards
            if (count($currentChunk) > 0) {
                $this->insertCards($currentChunk);
                $processedCount += count($currentChunk);
            }

            fclose($handle);
            $bar->finish();

            // Commit the transaction
            DB::commit();

            $this->info("\nSuccessfully imported {$processedCount} cards");

        } catch (\Exception $e) {
            // Roll back the transaction in case of error
            DB::rollBack();

            $this->error("\nImport failed: " . $e->getMessage());
            return 1;
        }

        return 0;
    }

    /**
     * Insert card data into the database
     */
    private function insertCards(array $cards)
    {
        // Using updateOrInsert to handle duplicate scryfall_ids
        foreach ($cards as $card) {
            Card::updateOrCreate(
                ['scryfall_id' => $card['scryfall_id']],
                $card
            );
        }
    }
}
