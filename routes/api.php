<?php

Route::group(['prefix' => 'v1', 'namespace' => 'v1'], function (){
    //登陆
    Route::post('login', 'AuthController@login');
    Route::get('test', 'TestController@test');
    Route::group(['middleware' => ['jwt.auth', 'jwt.refresh']], function(){
        //演出
        Route::post('performance/page', 'PerformanceController@page');
        Route::get('performance/baseData', 'PerformanceController@fetchBaseData');
        Route::post('performance/store', 'PerformanceController@store');
        Route::post('performance/update', 'PerformanceController@update');
        Route::post('performance/upload', 'PerformanceController@upload');
        Route::get('performance/delete/{perf_id}', 'PerformanceController@delete');

        //演员
        Route::post('actor/page', 'ActorController@page');
        Route::get('actor/baseData', 'ActorController@fetchBaseData');
        Route::post('actor/searchActors', 'ActorController@searchActors');
        Route::post('actor/store', 'ActorController@store');
        Route::post('actor/update', 'ActorController@update');
        Route::post('actor/upload', 'ActorController@upload');
        Route::get('actor/delete/{actor_id}', 'ActorController@delete');
        
        //工具表
            //剧团
            Route::post('troupe/page', 'utils\TroupeController@page');
            Route::post('troupe/store', 'utils\TroupeController@store');
            Route::post('troupe/update', 'utils\TroupeController@update');
            Route::get('troupe/delete/{troupe_id}', 'utils\TroupeController@delete');

            //剧种
            Route::post('type/page', 'utils\TypeController@page');
            Route::post('type/store', 'utils\TypeController@store');
            Route::post('type/update', 'utils\TypeController@update');
            Route::get('type/delete/{type_id}', 'utils\TypeController@delete');

            //演出地址
            Route::post('addr/page', 'utils\AddrController@page');
            Route::post('addr/store', 'utils\AddrController@store');
            Route::post('addr/update', 'utils\AddrController@update');
            Route::get('addr/delete/{addr_id}', 'utils\AddrController@delete');

            //备份类型
            Route::post('baktype/page', 'utils\BaktypeController@page');
            Route::post('baktype/store', 'utils\BaktypeController@store');
            Route::post('baktype/update', 'utils\BaktypeController@update');
            Route::get('baktype/delete/{baktype_id}', 'utils\BaktypeController@delete');
    });
    Route::post('file/upload', 'FileController@upload');        //验证可以放后面做
});