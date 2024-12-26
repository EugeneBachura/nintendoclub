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
        Schema::table('levels', function (Blueprint $table) {
            $table->integer('coins')->default(0)->after('description');
            $table->integer('premium_points')->default(0)->after('coins');
            $table->unsignedBigInteger('item_id')->nullable()->after('premium_points');
            $table->unsignedBigInteger('badge_id')->nullable()->after('item_id');

            $table->foreign('item_id')->references('id')->on('items')->onDelete('set null');
            $table->foreign('badge_id')->references('id')->on('badges')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('levels', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
            $table->dropForeign(['badge_id']);
            $table->dropColumn('coins');
            $table->dropColumn('premium_points');
            $table->dropColumn('item_id');
            $table->dropColumn('badge_id');
        });
    }
};
