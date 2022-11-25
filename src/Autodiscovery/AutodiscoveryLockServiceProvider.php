<?php

namespace Goedemiddag\AutodiscoveryLock\Autodiscovery;

use Goedemiddag\AutodiscoveryLock\Commands\AutodiscoverPackageLock;

class AutodiscoveryLockServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                AutodiscoverPackageLock::class
            ]);
        }
    }
}
