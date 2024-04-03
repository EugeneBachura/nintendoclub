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
            $table->integer('experience')->default(0); // опыт
            $table->integer('coins')->default(0); // монеты
            $table->integer('level')->default(1); // уровень
            $table->date('birthday')->nullable(); // д.р.
            $table->integer('premium_points')->default(0); // премиум очки
            $table->integer('comment_count')->default(0); // количество комментариев
            $table->text('profile_description')->nullable(); // описание профиля
            $table->json('favorite_games')->nullable(); // JSON для списка любимых игр
            $table->timestamp('last_active_at')->nullable(); // последняя активность
            $table->integer('reputation_count')->default(0); // репутация
            $table->integer('daily_visits_count')->default(0); // счётчик ежедневных посещений 
            $table->timestamp('last_reward_collected_at')->nullable(); // дата и время, когда последний раз была собрана награда
            $table->integer('consecutive_days')->default(0); // счётчик, который показывает, сколько дней подряд собрана награда
            $table->json('notifications')->nullable(); // уведомления
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
