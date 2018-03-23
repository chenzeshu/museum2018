<?php

use Illuminate\Http\Request;


Route::group(['prefix' => 'v1', 'namespace' => 'v1'], function (){
    //登陆
    Route::post('login', 'AuthController@login');
    Route::get('test', 'TestController@test');
    Route::group(['middleware' => ['jwt.auth', 'jwt.refresh']], function(){
        Route::post('performance/page', 'PerformanceController@page');
    });

});