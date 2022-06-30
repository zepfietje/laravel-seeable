<?php

namespace ZepFietje\Seeable\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use ZepFietje\Seeable\SeeableServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            SeeableServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
    }
}
