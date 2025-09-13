<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Drive;

class GoogleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // One client, both scopes
        $this->app->singleton(Google_Client::class, function () {
            $client = new Google_Client();
            $client->setAuthConfig(storage_path(config('services.google.sa_json')));
            $client->setScopes([
                Google_Service_Calendar::CALENDAR_READONLY,
                Google_Service_Drive::DRIVE_READONLY,
            ]);
            return $client;
        });

        // Concrete Google APIs that reuse the same client
        $this->app->singleton(Google_Service_Calendar::class, function ($app) {
            return new Google_Service_Calendar($app->make(Google_Client::class));
        });

        $this->app->singleton(Google_Service_Drive::class, function ($app) {
            return new Google_Service_Drive($app->make(Google_Client::class));
        });
    }
}