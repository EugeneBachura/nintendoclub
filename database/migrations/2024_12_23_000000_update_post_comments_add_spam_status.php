<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePostCommentsAddSpamStatus extends Migration
{
    public function up()
    {
        Schema::table('post_comments', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected', 'spam'])->default('pending')->change();
        });
    }

    public function down()
    {
        Schema::table('post_comments', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->change();
        });
    }
}
