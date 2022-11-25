<?php

namespace Goedemiddag\AutodiscoveryLock\Autodiscovery;

use Goedemiddag\AutodiscoveryLock\Commands\AutodiscoveryPackageLock;
use Goedemiddag\AutodiscoveryLock\Commands\AutodiscoveryPackageLockVerify;

class AutodiscoveryLockServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                AutodiscoveryPackageLock::class,
                AutodiscoveryPackageLockVerify::class
            ]);
        }
    }
}
