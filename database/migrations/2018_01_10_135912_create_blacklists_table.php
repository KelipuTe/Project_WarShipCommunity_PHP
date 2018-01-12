<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlacklistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blacklists', function (Blueprint $table) {
            $table->increments('id');
            /*
             * 目标类型，使用名字对应
             * 如果被拉黑的是用户，这里的 type 值就是 user
             * 如果被拉黑的是讨论，这里的 type 值就是 discussion
             */
            $table->string('type');
            $table->integer('target'); // 目标对应 id
            $table->string('explain'); // 拉黑解释
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blacklists');
    }
}
