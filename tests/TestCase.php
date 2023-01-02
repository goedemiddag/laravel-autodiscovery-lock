<?php

namespace Goedemiddag\AutodiscoveryLock\Tests;

use Goedemiddag\AutodiscoveryLock\Autodiscovery\AutodiscoveryLockServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * Load the package service provider. This makes sure that, for example the config files are available.
     */
    protected function getPackageProviders($app): array
    {
        return [
            AutodiscoveryLockServiceProvider::class,
        ];
    }
}
