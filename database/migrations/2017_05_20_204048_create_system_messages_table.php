<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemMessagesTable extends Migration
{

    public function up()
    {
        Schema::create('system_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('behavior');
            $table->index(['user_id','behavior']);
            $table->string('admin_name');
            $table->string('comment');
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('system_messages');
    }
}