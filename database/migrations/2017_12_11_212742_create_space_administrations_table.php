<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpaceAdministrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('space_administrations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title'); // 卫星标题
            $table->text('body'); // 卫星内容
            $table->integer('user_id')->unsigned(); // 发射卫星的用户
            $table->integer('ontrack')->default(1); // 是否在轨
            $table->timestamp('destroyed_at')->nullable(); // 击落卫星的时间
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
        Schema::dropIfExists('space_administrations');
    }
}
