<?php

namespace NotificationChannels\Ideamart;

use Illuminate\Support\ServiceProvider;
use Joomtriggers\Ideamart\SMS\Handler;
use Joomtriggers\Ideamart\Handler as BaseHandler;

class IdeamartServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->when(IdeamartChannel::class)
            ->needs(Handler::class)
            ->give(function () {
                $config = $this->app['config']['services.ideamart'];
                $handler = (new BaseHandler())->sms();
                $handler->setApplication($config['application']);
                $handler->setSecret($config['password']);
                $handler->setServer(isset($config['server']) ? $config['server'] : "https://api.dialog.lk/sms/send/");

                return $handler;
            });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
    }
}
