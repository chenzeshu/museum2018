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
    });
    Route::post('file/upload', 'FileController@upload');        //验证可以放后面做
});