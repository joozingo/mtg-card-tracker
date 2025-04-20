<?php

namespace App\Console\Commands;

use App\Models\Card;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ImportScryfallData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-scryfall-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download latest Scryfall Oracle Cards data and import it';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $bulkDataType = 'default-cards';
        $scryfallApiUrl = "https://api.scryfall.com/bulk-data/{$bulkDataType}";
        $downloadedFilePath = null;

        $this->info("Fetching latest Scryfall bulk data information ({$bulkDataType})...");

        try {
            // 1. Get Bulk Data Info
            $response = Http::withoutVerifying()->get($scryfallApiUrl);

            if (!$response->successful()) {
                $this->error("Failed to fetch bulk data info from Scryfall. Status: " . $response->status());
                return 1;
            }

            $bulkDataInfo = $response->json();
            $downloadUri = $bulkDataInfo['download_uri'] ?? null;
            $bulkDataUpdatedAt = $bulkDataInfo['updated_at'] ?? 'unknown';

            if (!$downloadUri) {
                $this->error("Could not find download URI in Scryfall response.");
                return 1;
            }

            $this->info("Latest data timestamp: {$bulkDataUpdatedAt}");

            // 2. Prepare Download
            $filename = basename($downloadUri);
            $targetPath = storage_path("app/private/{$filename}"); // Full path for Http::sink
            $relativeTargetPath = "{$filename}"; // Relative path for Storage facade

            // Check if this exact file already exists
            if (Storage::disk('local')->exists($relativeTargetPath)) {
                $this->info("Latest data file ({$filename}) already downloaded. Skipping download.");
                $downloadedFilePath = $targetPath;
            } else {
                // 3. Download File
                $this->info("Downloading {$filename} to storage...");
                $downloadResponse = Http::withoutVerifying()->sink($targetPath)->get($downloadUri);

                if (!$downloadResponse->successful()) {
                    // Clean up potentially partial file
                    Storage::disk('local')->delete($relativeTargetPath);
                    $this->error("Download failed. Status: " . $downloadResponse->status());
                    return 1;
                }
                $this->info("Download complete.");
                $downloadedFilePath = $targetPath;
            }

        } catch (\Exception $e) {
            $this->error("An error occurred during download/preparation: " . $e->getMessage());
            return 1;
        }

        // Proceed with import using the downloaded file path
        if (!$downloadedFilePath) {
            $this->error("Could not determine file path for import.");
            return 1;
        }

        $filePath = $downloadedFilePath; // Use the downloaded file
        $chunkSize = 1000;

        $this->info("\nImporting Scryfall data from {$filename}"); // Use filename for message
        $this->info("This may take a while depending on the file size...");

        // Truncate the table FIRST (outside the main import transaction)
        try {
            $this->info("Disabling foreign key checks...");
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            $this->info("Truncating existing cards data...");
            DB::table('cards')->truncate();
            $this->info("Re-enabling foreign key checks."); // Corrected message
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        } catch (\Exception $e) {
            // If truncate fails, re-enable checks if possible and exit
            $this->error("Failed to truncate table: " . $e->getMessage());
            DB::statement('SET FOREIGN_KEY_CHECKS=1;'); // Attempt to re-enable
            return 1;
        }

        // Now, begin transaction specifically for the import process
        DB::beginTransaction();
        try {
            // Read the file
            $this->info("Starting card import process..."); // Added info message
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

            // Cleanup downloaded Scryfall data files
            $this->info("Cleaning up downloaded Scryfall data files...");
            Storage::disk('local')->delete($relativeTargetPath);
            $this->info("Cleanup complete.");

        } catch (\Exception $e) {
            // Roll back the transaction in case of error during import
            DB::rollBack();

            $this->error("\nImport failed during card processing: " . $e->getMessage());
            return 1;
        }

        // After card import and cleanup
        $this->info("
Importing card data complete. Now fetching rulings bulk data...");
        // Fetch rulings bulk-data info
        $rulingsType = 'rulings';
        $rulingsInfoResponse = Http::withoutVerifying()->get("https://api.scryfall.com/bulk-data/{$rulingsType}");
        if ($rulingsInfoResponse->successful()) {
            $rulingsInfo = $rulingsInfoResponse->json();
            $rulingsUri = $rulingsInfo['download_uri'] ?? null;
            if ($rulingsUri) {
                $rulingsFile = basename($rulingsUri);
                $rulingsPath = storage_path("app/private/{$rulingsFile}");
                // Download rulings file
                Http::withoutVerifying()->sink($rulingsPath)->get($rulingsUri);
                $handle = fopen($rulingsPath, 'r');
                if ($handle) {
                    // Truncate existing rulings

                    \App\Models\Ruling::truncate();

                    while (!feof($handle)) {
                        $line = fgets($handle);
                        if (!$line || trim($line) === '[' || trim($line) === ']')
                            continue;
                        if (substr(rtrim($line), -1) === ',') {
                            $line = substr(rtrim($line), 0, -1);
                        }
                        $data = json_decode($line, true);
                        if (isset($data['oracle_id'], $data['comment'], $data['published_at'])) {
                            \App\Models\Ruling::updateOrCreate(
                                [
                                    'oracle_id' => $data['oracle_id'],
                                    'published_at' => $data['published_at'],
                                ],
                                ['comment' => $data['comment']]
                            );
                        }
                    }
                    fclose($handle);
                    $this->info('Rulings imported successfully.');
                }
                // Cleanup rulings file
                Storage::disk('local')->delete($rulingsFile);
            }
        }
        // End of rulings import

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
