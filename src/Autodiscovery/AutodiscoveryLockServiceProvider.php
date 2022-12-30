<?php

namespace Goedemiddag\AutodiscoveryLock\Autodiscovery;

use Goedemiddag\AutodiscoveryLock\Commands\AutodiscoveryPackageLock;
use Goedemiddag\AutodiscoveryLock\Commands\AutodiscoveryPackageLockVerify;
use Illuminate\Support\ServiceProvider;

class AutodiscoveryLockServiceProvider extends ServiceProvider
{
    protected const CONFIG_PATH = __DIR__ . '/../../config/autodiscovery.php';

    public function register(): void
    {
        $this->mergeConfigFrom(self::CONFIG_PATH, 'autodiscovery');
    }

    public function boot(): void
    {
        $this->publishes([self::CONFIG_PATH => config_path('autodiscovery.php.')], 'config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                AutodiscoveryPackageLock::class,
                AutodiscoveryPackageLockVerify::class
            ]);
        }
    }
}
