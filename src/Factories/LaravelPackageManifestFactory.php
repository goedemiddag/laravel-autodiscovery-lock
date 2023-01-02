<?php

namespace Goedemiddag\AutodiscoveryLock\Factories;

use Goedemiddag\AutodiscoveryLock\Autodiscovery\LaravelPackageManifest;
use Illuminate\Foundation\PackageManifest;

class LaravelPackageManifestFactory
{
    public function build(PackageManifest $manifest): LaravelPackageManifest
    {
        /** @var string $basePath */
        $basePath = config('autodiscovery.base_path', $manifest->basePath);

        return new LaravelPackageManifest(
            files: $manifest->files,
            basePath: $basePath,
            manifestPath: $manifest->manifestPath ?? ''
        );
    }
}
