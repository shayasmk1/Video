<?php namespace App\Providers;

use Storage;
use League\Flysystem\Filesystem;
use Spatie\Dropbox\Client AS DropboxClient;
use Spatie\FlysystemDropbox\DropboxAdapter;
use Illuminate\Support\ServiceProvider;

class DropboxFilesystemServiceProvider extends ServiceProvider {

    public function boot()
    {
        Storage::extend('dropbox', function($app, $config)
        {
            $client = new DropboxClient($config['accessToken']);
            return new Filesystem(new DropboxAdapter($client));
        });
    }

    public function register()
    {
        //
    }

}