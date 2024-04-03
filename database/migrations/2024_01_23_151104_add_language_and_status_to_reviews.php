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
        Schema::table('reviews', function (Blueprint $table) {
            $table->string('language')->default('en');
            $table->enum('status', ['pending', 'published', 'closed'])->default('pending');
            $table->text('status_text')->nullable();
            $table->integer('awards')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn('language');
            $table->dropColumn('status');
            $table->dropColumn('status_text');
            $table->dropColumn('awards');
        });
    }
};
