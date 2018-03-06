<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntroductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('introductions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title'); // 简介标题
            $table->text('body'); // 简介内容
            $table->integer('user_id')->unsigned(); // 发表的用户
            $table->integer('last_user_id')->unsigned(); // 最后更新的用户
            $table->boolean('blacklist')->default(false); // 黑名单
            $table->softDeletes(); // 软删除
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
        Schema::dropIfExists('introductions');
    }
}
