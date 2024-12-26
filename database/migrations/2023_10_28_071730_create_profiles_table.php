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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->integer('experience')->default(0);
            $table->integer('coins')->default(0);
            $table->integer('level')->default(1);
            $table->date('birthday')->nullable();
            $table->integer('premium_points')->default(0);
            $table->integer('comment_count')->default(0);
            $table->text('profile_description')->nullable();
            $table->json('favorite_games')->nullable();
            $table->timestamp('last_active_at')->nullable();
            $table->integer('reputation_count')->default(0);
            $table->integer('daily_visits_count')->default(0);
            $table->timestamp('last_reward_collected_at')->nullable();
            $table->integer('consecutive_days')->default(0);
            $table->json('notifications')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
