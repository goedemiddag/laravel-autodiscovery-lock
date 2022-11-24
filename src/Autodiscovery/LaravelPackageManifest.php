<?php

namespace Goedemiddag\AutodiscoveryLock\Autodiscovery;

use Illuminate\Filesystem\Filesystem;

class LaravelPackageManifest extends \Illuminate\Foundation\PackageManifest
{
    public function getManifest()
    {
        return parent::getManifest();
    }
}