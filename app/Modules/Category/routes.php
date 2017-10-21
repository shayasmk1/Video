<?php

Route::group(['middleware' => ['web'], 'prefix' => 'api/v1', 'namespace' => 'App\Modules\Category\Controllers'], function(){
    Route::resource('category', 'CategoryApiController',['only' => [
        'index', 'show'
    ]]);
});