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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->enum('status', ['hidden', 'under_review', 'deleted', 'active']);
            $table->foreignId('author_id')->constrained('users');
            $table->foreignId('reviewer_id')->nullable()->constrained('users');
            $table->string('alias')->nullable();
            $table->string('language', 2)->default('en');
            $table->unsignedBigInteger('views_count')->default(0);
            $table->bigInteger('popularity')->default(0);

            $table->string('title');
            $table->text('content');
            $table->text('seo_description')->nullable();
            $table->string('keywords')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
