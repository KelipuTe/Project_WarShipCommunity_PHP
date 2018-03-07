<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFactoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('factories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('preview')->nullable(); // 预览图
            $table->string('view_front')->nullable(); // 前视图
            $table->string('view_back')->nullable(); // 后视图
            $table->string('view_left')->nullable(); // 左视图
            $table->string('view_right')->nullable(); // 右视图
            $table->string('view_top')->nullable(); // 顶视图
            $table->string('view_bottom')->nullable(); // 底视图
            $table->string('file')->nullable();  // 文件路径
            $table->integer('user_id')->unsigned(); // 创建的用户
            $table->boolean('satellite')->default(false);  // 是否是卫星
            $table->integer('satellite_id')->default(0); // 对应的卫星
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
        Schema::dropIfExists('factories');
    }
}
