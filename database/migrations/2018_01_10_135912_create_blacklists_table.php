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
            $table->string('explain'); // 举报解释
            $table->integer('user_id')->unsigned(); // 举报的用户
            $table->boolean('done')->default(false); // 处理状态
            $table->string('admin_opinion')->nullable(); // 管理员意见
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
