<?php

Route::group(['middleware' => ['web', 'general-access'], 'prefix' => 'api/v1/settings', 'namespace' => 'App\Modules\Settings\Controllers'], function(){
    Route::post('color', 'SettingsApiController@updateColor');
    
});
