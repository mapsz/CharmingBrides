<?php

namespace App\Providers;


use Illuminate\Support\ServiceProvider;

// use App\WebSocketHandlers\ClientSocketHandler;
// use BeyondCode\LaravelWebSockets\Facades\WebSocketsRouter;
// use Symfony\Component\Console\Output\NullOutput;
// use BeyondCode\LaravelWebSockets\Server\Logger\WebsocketsLogger;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // app()->singleton(WebsocketsLogger::class, function () {
        //     return (new WebsocketsLogger(new NullOutput()))->enable(false);
        // });
        // WebSocketsRouter::webSocket('/app/{appKey}/{apiKey}', ClientSocketHandler::class);
    }
}