<?php
Route::group(['middleware' => ['web'], 'prefix' => 'api/v1/auth', 'namespace' => 'App\Modules\Auth\Controllers'], function(){
    Route::post('login', 'AuthController@login');
});


Route::group(['middleware' => ['web'], 'prefix' => 'auth', 'namespace' => 'App\Modules\Auth\Controllers'], function(){
    Route::get('confirm/{confirmation_code}/{reconfirm_code}/{UUID}', 'AuthController@activateUser1');
});


