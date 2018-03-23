<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /******* 演员相关表集合 *********/
        //演员主表
        Schema::create('actors', function (Blueprint $table) {
            $table->increments('actor_id')->comment('演员id');
            $table->string('actor_name',32)->comment('演员姓名');
            $table->enum('actor_sex',['男', '女'])->default('男')->comment('演员性别');
            $table->unsignedTinyInteger('actor_age')->nullable()->comment('演员年龄');
            $table->string('actor_record',600)->nullable()->comment('演员获奖经历');
            $table->timestamps();
        });
        //演员照片表
        Schema::create('actor_pics', function (Blueprint $table) {
            $table->increments('ap_id')->comment('演员照片id');
            $table->unsignedInteger('actor_id')->comment('演员id');
            $table->string('pic_path',128)->comment('照片存储路径');
            $table->timestamps();

            $table->index('actor_id')->comment('索引:演员id');
        });
        //演员所在剧团历史表
        Schema::create('actor_troupe_histories', function (Blueprint $table) {
            $table->increments('ath_id')->comment('历史id');
            $table->unsignedInteger('troupe_id')->comment('剧团id');
            $table->unsignedInteger('actor_id')->comment('演员id');
            $table->timestamps();

            $table->index('actor_id')->comment('索引：用于根据此演员找出其带过的剧团');
            $table->index('troupe_id')->comment('索引：用于统计待过这个剧团的演员');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('actors');
        Schema::dropIfExists('actor_pics');
        Schema::dropIfExists('actor_troupe_histories');
    }
}
