<?php
Route::group(['middleware' => ['web', 'general-access'], 'prefix' => 'api/v1', 'namespace' => 'App\Modules\User\Controllers'], function(){
    Route::get('user/tag/custom', 'UserTagApiController@addCustom');
    Route::resource('user/tag', 'UserTagApiController');
    
    Route::get('users/me','UserApiController@me');
    Route::put('users','UserApiController@update');
    Route::delete('users','UserApiController@destroy');
    Route::get('users/activate','UserApiController@activate');
    Route::get('users/deactivate','UserApiController@deactivate');
});

Route::group(['middleware' => ['web'], 'prefix' => 'api/v1', 'namespace' => 'App\Modules\User\Controllers'], function(){
    Route::resource('users', 'UserApiController',['only' => [
        'store', 'index'
    ]]);
});

Route::group(['middleware' => ['web', 'general-temp-access'], 'prefix' => 'api/v1', 'namespace' => 'App\Modules\User\Controllers'], function(){
    Route::resource('users', 'UserApiController',['only' => [
        'show'
    ]]);
});

Route::group(['middleware' => ['web'], 'prefix' => 'api/v1', 'namespace' => 'App\Modules\User\Controllers'], function(){
    
    Route::get('user/search/{name}/{no_of_results}', 'UserApiController@searchUsers');
});

/* Reports */
Route::group(['middleware' => ['web', 'general-access'], 'prefix' => 'api/v1/user/reports', 'namespace' => 'App\Modules\User\Controllers'], function(){
    
    Route::get('statistics/user/tag','ReportsApiController@userStatisticsOverTags');
    Route::post('statistics/tag/users/count','ReportsApiController@tagStatisticsOverUser');
    Route::post('statistics/video/{video_id}/traffic','ReportsApiController@videoTraffic');
    
    Route::get('statistics/user/tag/channel','ReportsApiController@userStatisticsOverTagsChannel');
    Route::post('statistics/tag/users/count/channel','ReportsApiController@tagStatisticsOverUserChannel');
    Route::post('statistics/channel/{channel_id}/traffic','ReportsApiController@channelTraffic');
});