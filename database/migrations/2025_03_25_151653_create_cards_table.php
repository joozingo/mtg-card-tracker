<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->string('scryfall_id')->unique();
            $table->string('name');
            $table->string('oracle_id')->nullable();
            $table->text('oracle_text')->nullable();
            $table->string('mana_cost')->nullable();
            $table->string('type_line')->nullable();
            $table->string('set')->nullable();
            $table->string('set_name')->nullable();
            $table->string('rarity')->nullable();
            $table->decimal('cmc', 12, 2)->nullable();
            $table->string('power')->nullable();
            $table->string('toughness')->nullable();
            $table->string('loyalty')->nullable();
            $table->string('colors')->nullable();
            $table->string('color_identity')->nullable();
            $table->string('layout')->nullable();
            $table->boolean('reserved')->default(false);
            $table->string('artist')->nullable();
            $table->string('collector_number')->nullable();
            $table->string('image_uri_small')->nullable();
            $table->string('image_uri_normal')->nullable();
            $table->string('image_uri_large')->nullable();
            $table->decimal('price_usd', 10, 2)->nullable();
            $table->decimal('price_eur', 10, 2)->nullable();
            $table->json('keywords')->nullable();
            $table->json('legalities')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
