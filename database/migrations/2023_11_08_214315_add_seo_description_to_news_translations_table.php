<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('news_translations', function (Blueprint $table) {
            $table->text('seo_description')->nullable()->after('content');
        });
    }

    public function down()
    {
        Schema::table('news_translations', function (Blueprint $table) {
            $table->dropColumn('seo_description');
        });
    }
};
