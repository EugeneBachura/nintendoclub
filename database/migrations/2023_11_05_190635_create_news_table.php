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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->enum('status', ['hidden', 'under_review', 'deleted', 'active']);
            $table->foreignId('author_id')->constrained('users');
            $table->foreignId('reviewer_id')->nullable()->constrained('users');
            $table->string('alias');
            $table->string('video')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
