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
        /******* 平台用户表集合 *********/

        Schema::create('users', function (Blueprint $table) {
            $table->increments('user_id')->comment('用户表序号');
            $table->string('user_nickname',32)->comment('用户昵称');
            $table->char('user_pass', 64)->comment('密码');
            $table->string('user_phone',32)->unique()->comment('用于登陆');
            $table->string('user_email',64)->unique()->nullable()->comment('邮箱');
            $table->string('user_openid',32)->unique()->nullable()->comment('用于微信');
            $table->unsignedSmallInteger('user_scope')->default(10000)->comment('权限等级');
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
