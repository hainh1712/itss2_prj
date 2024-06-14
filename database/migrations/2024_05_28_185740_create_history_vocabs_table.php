<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('history_vocabs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('history_id')->constrained('histories');
            $table->foreignId('vocab_id')->constrained('vocabularies');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_vocabs');
    }
};
