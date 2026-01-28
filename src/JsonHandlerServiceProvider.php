<?php

declare(strict_types=1);

namespace SMartins\Exceptions;

use Illuminate\Support\ServiceProvider;

class JsonHandlerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            $this->configPath() => config_path('json-exception-handler.php'),
        ]);

        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'exception');

        $this->publishes([
            __DIR__.'/../resources/lang' => $this->app->langPath('vendor/exception'),
        ]);
    }

    public function register(): void
    {
        $this->mergeConfigFrom($this->configPath(), 'json-exception-handler');
    }

    public function configPath(): string
    {
        return __DIR__.'/../config/json-exception-handler.php';
    }
}
