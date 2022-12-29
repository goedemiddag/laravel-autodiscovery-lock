<?php

namespace Goedemiddag\AutodiscoveryLock\Autodiscovery;

use Goedemiddag\AutodiscoveryLock\Commands\AutodiscoveryPackageLock;
use Goedemiddag\AutodiscoveryLock\Commands\AutodiscoveryPackageLockVerify;
use Illuminate\Support\ServiceProvider;

class AutodiscoveryLockServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $configPath = __DIR__ . '/../../config/autodiscovery.php';
        $this->mergeConfigFrom($configPath, 'autodiscovery');
    }

    public function boot(): void
    {
        $configPath = __DIR__ . '/../../config/autodiscovery.php';
        $this->publishes([$configPath => config_path('autodiscovery.php.')], 'config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                AutodiscoveryPackageLock::class,
                AutodiscoveryPackageLockVerify::class
            ]);
        }
    }
}
