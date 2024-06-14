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
        Schema::table('cards', function (Blueprint $table) {
            $table->dropForeign(['memo_id']);
            $table->dropColumn('memo_id');
            $table->unsignedBigInteger('example_id')->nullable();
            $table->foreign('example_id')->references('id')->on('examples')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cards', function (Blueprint $table) {
            $table->dropForeign(['example_id']);
            $table->dropColumn('example_id');
            $table->unsignedBigInteger('memo_id')->nullable();
            $table->foreign('memo_id')->references('id')->on('memos')->onDelete('cascade');
        });
    }
};
