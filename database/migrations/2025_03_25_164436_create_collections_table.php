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
        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('card_id')->constrained('cards')->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->enum('condition', ['mint', 'near_mint', 'excellent', 'good', 'light_played', 'played', 'poor'])->default('near_mint');
            $table->boolean('foil')->default(false);
            $table->decimal('purchase_price', 10, 2)->nullable();
            $table->date('acquired_date')->nullable();
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collections');
    }
};
