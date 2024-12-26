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
        Schema::create('old_user_data', function (Blueprint $table) {
            $table->id();
            $table->string('discord_id')->unique();
            $table->integer('money')->nullable();
            $table->integer('donat')->nullable();
            $table->integer('experience')->nullable();
            $table->integer('level')->default(1);
            $table->integer('boost')->nullable();
            $table->string('sw_code')->nullable();
            $table->date('birthday')->nullable();
            $table->integer('ticket_count')->nullable();
            $table->integer('last_birthday_year')->nullable();
            $table->integer('message_count')->nullable();
            $table->integer('boss_hit_count')->nullable();
            $table->integer('word_game_score')->nullable();
            $table->boolean('is_banned')->default(false);
            $table->integer('total_donat')->nullable();
            $table->integer('pokemon_game_score')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('old_user_data');
    }
};
