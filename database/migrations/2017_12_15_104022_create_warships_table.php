<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarshipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warships', function (Blueprint $table) {
            $table->increments('id');
            $table->string('classes'); // 舰船级别
            $table->string('name'); // 舰船名字
            $table->string('no'); // 舰船弦号
            $table->string('type'); // 舰船类型
            $table->string('country'); // 舰船国籍
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
        Schema::dropIfExists('warships');
    }
}
