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
            $table->string('discord_id')->unique(); // id в Discord
            $table->integer('money')->nullable(); // монеты
            $table->integer('donat')->nullable(); // премиум очки
            $table->integer('experience')->nullable(); // опыт
            $table->integer('level')->default(1); // уровень
            $table->integer('boost')->nullable(); // множитель бонуса
            $table->string('sw_code')->nullable(); // sw-код друга
            $table->date('birthday')->nullable(); // дата рождения
            $table->integer('ticket_count')->nullable(); // количество билетов
            $table->integer('last_birthday_year')->nullable(); // в каком году отмечалось последний раз день рождение
            $table->integer('message_count')->nullable(); // количество сообщений на сервере
            $table->integer('boss_hit_count')->nullable(); // количество ударов по боссу
            $table->integer('word_game_score')->nullable(); // количество очков в игре слов
            $table->boolean('is_banned')->default(false); // забанен или нет
            $table->integer('total_donat')->nullable(); // премиум очки за всё время
            $table->integer('pokemon_game_score')->nullable(); // количество очков в игре покемонов
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
