<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscussionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discussions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');//论坛文章标题
            $table->string('body');//论坛文章内容
            $table->integer('nice')->default(0);//论坛精华文章
            $table->integer('user_id')->unsigned();//发表的用户
            $table->integer('last_user_id')->unsigned();//最后更新的用户
            $table->timestamp('published_at');//发表时间
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
        Schema::dropIfExists('discussions');
    }
}
