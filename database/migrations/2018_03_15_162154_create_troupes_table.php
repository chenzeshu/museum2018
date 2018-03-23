<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTroupesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /******* 公用表集合 *********/

        //剧团
        Schema::create('troupes', function (Blueprint $table) {
            $table->increments('troupe_id');
            $table->string('troupe_name')->comment('剧团名称');
            $table->timestamps();
        });

        //剧种
        Schema::create('types', function (Blueprint $table) {
            $table->increments('type_id');
            $table->string('type_name')->comment('剧种名称');
            $table->timestamps();
        });

        //备份类型
        Schema::create('baktypes', function (Blueprint $table) {
            $table->increments('baktype_id');
            $table->string('baktype_name', 30)->comment('备份类型名称');
            $table->timestamps();
        });

        //演出地点
        Schema::create('addrs', function (Blueprint $table) {
            $table->increments('addr_id');
            $table->string('addr_name',60)->comment('演出地点');
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
        Schema::dropIfExists('troupes');
        Schema::dropIfExists('types');
        Schema::dropIfExists('baktypes');
        Schema::dropIfExists('addrs');
    }
}
