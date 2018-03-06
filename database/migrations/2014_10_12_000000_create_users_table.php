<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username')->unique(); // 用户名
            $table->string('email')->unique(); // email
            $table->string('email_confirm_code'); // email 验证码
            $table->integer('email_confirm')->default(0); // email 验证状态
            $table->string('password'); // 密码
            $table->string('avatar'); // 头像
            $table->integer('power')->default(0); // 权限
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
