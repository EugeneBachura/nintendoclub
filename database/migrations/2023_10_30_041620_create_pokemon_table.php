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
        Schema::create('pokemon', function (Blueprint $table) {
            $table->id();
            $table->integer('pokedex_number');
            $table->string('name');
            $table->string('image_url');
            $table->string('shiny_image_url')->nullable();
            $table->text('description');
            $table->boolean('is_legendary')->default(false);
            $table->integer('evolution_pokemon_id')->nullable();
            $table->json('types');
            $table->json('stats');
            $table->string('gender')->default('both'); // male, female, both, none
            $table->foreignId('pokemon_category_id')->constrained('pokemon_categories');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pokemon');
    }
};
