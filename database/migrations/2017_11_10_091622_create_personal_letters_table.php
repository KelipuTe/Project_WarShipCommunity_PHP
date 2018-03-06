<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonalLettersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personal_letters', function (Blueprint $table) {
            $table->increments('id');
            $table->text('body'); // 私信正文
            $table->integer('from_user_id')->unsigned(); // 私信发起人
            $table->integer('to_user_id')->unsigned(); // 私信接收人
            $table->integer('has_read')->default(0); // 阅读状态
            $table->timestamp('read_at')->nullable(); // 阅读时间
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
        Schema::dropIfExists('personal_letters');
    }
}
