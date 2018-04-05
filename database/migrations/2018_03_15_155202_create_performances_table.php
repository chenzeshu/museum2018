<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePerformancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /******* 演出相关表集合 *********/

        //演出主表
        Schema::create('performances', function (Blueprint $table) {
            $table->increments('perf_id')->comment('演出表序号');
            $table->unsignedInteger('perf_code')->comment('演出编号，规则2018999');
            $table->timestamp('perf_date')->comment('演出时间');
            $table->unsignedTinyInteger('perf_type')->nullable()->comment('剧种');
            $table->unsignedInteger('perf_troupe')->nullable()->comment('剧团');
            $table->unsignedInteger('perf_addr')->nullable()->comment('演出地点');
            $table->unsignedSmallInteger('perf_duration')->nullable()->comment('视频时长，分钟');
            $table->unsignedMediumInteger('perf_size')->nullable()->comment('视频大小，MB');
            $table->unsignedTinyInteger('perf_baktype')->default(1)->comment('备份类型');
            $table->timestamps();

            $table->index('perf_type')->comment('索引：去找剧种信息');
            $table->index('perf_troupe')->comment('索引：去找剧团信息');
            $table->index('perf_addr')->comment('索引：去找演出地点信息');
        });

        //演出细节表
        Schema::create('performance_details', function (Blueprint $table) {
            $table->increments('pd_id');
            $table->unsignedInteger('perf_id')->comment('演出id');
            $table->string('perf_content', 600)->nullable()->comment('演员id');
            $table->string('perf_receive', 600)->nullable()->comment('接收记录');
            $table->string('perf_output', 600)->nullable()->comment('输出记录');
            $table->string('perf_remark', 450)->nullable()->comment('备注');
            $table->timestamps();

            $table->index('perf_id')->comment('索引：找演出本体');
        });

        //演出相关演员表
        Schema::create('performance_actors', function (Blueprint $table) {
            $table->increments('pa_id')->comment('序号');
            $table->unsignedInteger('perf_id')->comment('演出id');
            $table->unsignedInteger('actor_id')->comment('演员id');
            $table->timestamps();

            $table->index('perf_id')->comment('索引：找演出本体');
            $table->index('actor_id')->comment('索引：找演员本体');
        });

        //演出-文件中间表
        Schema::create('performance_files', function (Blueprint $table) {
            $table->increments('pf_id');
            $table->unsignedInteger('perf_id')->comment('演出id');
            $table->unsignedInteger('file_id')->comment('文件id');
            $table->timestamps();

            $table->index('perf_id')->comment('索引：找演出本体');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('performances');
        Schema::dropIfExists('performance_details');
        Schema::dropIfExists('performance_actors');
        Schema::dropIfExists('performance_files');
        Schema::dropIfExists('performance_bak_records');
    }
}
