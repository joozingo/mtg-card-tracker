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
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('card_id')->constrained('cards')->onDelete('cascade');
            $table->integer('priority')->default(3); // 1 = high, 2 = medium, 3 = low
            $table->decimal('max_price', 10, 2)->nullable(); // Maximum price willing to pay
            $table->text('notes')->nullable();
            $table->timestamps();

            // Ensure unique card entries
            $table->unique(['card_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wishlists');
    }
};
