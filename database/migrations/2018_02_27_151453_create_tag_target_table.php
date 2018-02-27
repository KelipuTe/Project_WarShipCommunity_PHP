<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagTargetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tag_target', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tag_id'); // 标签 id
            /*
             * 目标类型，使用名字对应
             * 如果是讨论，这里的 type 值就是 discussion
             */
            $table->string('type'); // 目标类型
            $table->integer('target'); // 目标对应 id
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
        Schema::dropIfExists('tag_target');
    }
}
