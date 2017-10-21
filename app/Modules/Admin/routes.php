<?php
Route::group(['middleware' => ['web', 'admin-access'], 'prefix' => 'api/v1/admin', 'namespace' => 'App\Modules\Admin\Controllers'], function(){
    Route::resource('tag', 'AdminTagApiController');
    Route::get('tag/{id}/activate', 'AdminTagApiController@activate');
    Route::get('tag/{id}/deactivate', 'AdminTagApiController@deactivate');
});

Route::group(['middleware' => ['web'], 'prefix' => 'api/v1/admin/auth', 'namespace' => 'App\Modules\Admin\Controllers'], function(){
    Route::post('login', 'AdminAuthController@login');
});

/* Channel */
Route::group(['middleware' => ['web', 'admin-access'], 'prefix' => 'api/v1/admin', 'namespace' => 'App\Modules\Admin\Controllers'], function(){
    Route::resource('channel', 'AdminChannelApiController');
    Route::get('channel/{id}/activate', 'AdminChannelApiController@activate');
    Route::get('channel/{id}/deactivate', 'AdminChannelApiController@deactivate');
    
});

/* Video */
Route::group(['middleware' => ['web', 'admin-access'], 'prefix' => 'api/v1/admin', 'namespace' => 'App\Modules\Admin\Controllers'], function(){
    Route::resource('video', 'AdminVideoApiController');
    Route::get('video/search/{name}/{no_of_results}', 'AdminVideoApiController@searchVideos');
});

/* Comment */
Route::group(['middleware' => ['web', 'admin-access'], 'prefix' => 'api/v1/admin/video/{id}', 'namespace' => 'App\Modules\Admin\Controllers'], function(){
    Route::resource('comment', 'AdminCommentApiController');
});

/* Reply Comment */
Route::group(['middleware' => ['web', 'admin-access'], 'prefix' => 'api/v1/admin/video/{id}/comment/{commentID}', 'namespace' => 'App\Modules\Admin\Controllers'], function(){
    Route::resource('reply', 'AdminReplyCommentApiController');
});


/* User */
Route::group(['middleware' => ['web', 'admin-access'], 'prefix' => 'api/v1/admin', 'namespace' => 'App\Modules\Admin\Controllers'], function(){
    Route::resource('users', 'AdminUserApiController',['only' => [
        'update', 'destroy', 'index', 'show'
    ]]);
    
    Route::get('users/{id}/activate','AdminUserApiController@activate');
    Route::get('users/{id}/deactivate','AdminUserApiController@deactivate');
    Route::get('user/search/{name}/{no_of_results}', 'AdminUserApiController@searchUsers');
});


/* Reports */

Route::group(['middleware' => ['web', 'admin-access'], 'prefix' => 'api/v1/admin/reports', 'namespace' => 'App\Modules\Admin\Controllers'], function(){
    
    Route::get('statistics/user/{user_id}/tag','AdminReportsApiController@userStatisticsOverTags');
    Route::post('statistics/tag/users/count','AdminReportsApiController@tagStatisticsOverUser');
    Route::post('statistics/video/{video_id}/traffic','AdminReportsApiController@videoTraffic');
    
    Route::get('statistics/user/{user_id}/tag/channel','AdminReportsApiController@userStatisticsOverTagsChannel');
    Route::post('statistics/tag/users/count/channel','AdminReportsApiController@tagStatisticsOverUserChannel');
    Route::post('statistics/channel/{channel_id}/traffic','AdminReportsApiController@channelTraffic');
});