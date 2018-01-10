<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->text('body'); // 论坛文章回复内容
            $table->integer('hot_comment')->default(0); // 论坛热点回复
            $table->integer('nice_comment')->default(0); // 论坛精华回复
            $table->integer('blacklist')->default(0); // 黑名单
            $table->integer('user_id')->unsigned();
            $table->integer('discussion_id')->unsigned();
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
        Schema::dropIfExists('comments');
    }
}
