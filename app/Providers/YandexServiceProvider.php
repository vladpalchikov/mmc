<?php

namespace MMC\Providers;

use Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;

class YandexServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        Storage::extend('yandex', function ($app, $config) {
            $accessToken = env('YANDEX_TOKEN');
            $client = new \Arhitector\Yandex\Disk($accessToken);
            $adapter = new \Arhitector\Yandex\Disk\Adapter\Flysystem($client, \Arhitector\Yandex\Disk\Adapter\Flysystem::PREFIX_FULL);
            return new Filesystem($adapter);
        });
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}