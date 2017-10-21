<?php

Route::group(['middleware' => ['web', 'general-access'], 'prefix' => 'api/v1', 'namespace' => 'App\Modules\Channel\Controllers'], function(){
    Route::get('channel/me', 'ChannelApiController@myChannels');
    Route::resource('channel', 'ChannelApiController',['only' => [
        'store', 'update', 'destroy'
    ]]);
    Route::get('channel/{id}/activate', 'ChannelApiController@activate');
    Route::get('channel/{id}/deactivate', 'ChannelApiController@deactivate');
    
    Route::get('channel/{id}/subscribe', 'SubscriptionApiController@subscribe');
    Route::get('channel/{id}/unsubscribe', 'SubscriptionApiController@unsubscribe');
    
    Route::post('channel/{channelID}/{videoID}/current', 'ChannelApiController@currentChannelPosition');
});
Route::group(['middleware' => ['web','general-temp-access'], 'prefix' => 'api/v1', 'namespace' => 'App\Modules\Channel\Controllers'], function(){
    
    Route::resource('channel', 'ChannelApiController',['only' => [
        'show'
    ]]);
});

Route::group(['middleware' => ['web'], 'prefix' => 'api/v1', 'namespace' => 'App\Modules\Channel\Controllers'], function(){
    
    Route::resource('channel', 'ChannelApiController',['only' => [
        'index'
    ]]);
});

Route::group(['middleware' => ['web'], 'prefix' => 'api/v1', 'namespace' => 'App\Modules\Channel\Controllers'], function(){
    
    Route::get('channel/search/{name}/{no_of_results}', 'ChannelApiController@searchChannels');
});