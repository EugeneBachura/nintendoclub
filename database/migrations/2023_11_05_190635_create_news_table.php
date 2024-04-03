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
            $table->string('image')->nullable(); // Путь к изображению
            $table->enum('status', ['hidden', 'under_review', 'deleted', 'active']);
            $table->foreignId('author_id')->constrained('users'); // ID автора
            $table->foreignId('reviewer_id')->nullable()->constrained('users'); // ID пользователя, принявшего с проверки
            $table->string('alias'); // Алиас для URL
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
