<?php

declare(strict_types=1);

namespace SMartins\Exceptions\Tests;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as BaseTestCase;
use SMartins\Exceptions\JsonHandlerServiceProvider;
use SMartins\Exceptions\Tests\Fixtures\Exceptions\Handler;

abstract class TestCase extends BaseTestCase
{
    /**
     * Define environment setup.
     *
     * @param  Application  $app
     */
    protected function getEnvironmentSetUp($app): void
    {
        $app->singleton(
            ExceptionHandler::class,
            Handler::class
        );

        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'exceptions');
        $app['config']->set('database.connections.exceptions', [
            'driver' => 'sqlite',
            'database' => ':memory:',
        ]);
    }

    /**
     * @param  Application  $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            JsonHandlerServiceProvider::class,
        ];
    }
}
