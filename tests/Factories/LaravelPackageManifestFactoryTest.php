<?php

namespace Goedemiddag\AutodiscoveryLock\Tests\Factories;

use Goedemiddag\AutodiscoveryLock\Autodiscovery\LaravelPackageManifest;
use Goedemiddag\AutodiscoveryLock\Factories\LaravelPackageManifestFactory;
use Goedemiddag\AutodiscoveryLock\Tests\TestCase;
use Illuminate\Foundation\PackageManifest;
use Symfony\Component\Filesystem\Path;

class LaravelPackageManifestFactoryTest extends TestCase
{
    public function testBuild(): void
    {
        $laravelPackageManifestFactory = new LaravelPackageManifestFactory();
        $packageManifest = resolve(PackageManifest::class);

        $laravelPackageManifest = $laravelPackageManifestFactory->build($packageManifest);

        $this->assertInstanceOf(LaravelPackageManifest::class, $laravelPackageManifest);
        $this->assertSame(config('autodiscovery.base_path'), $laravelPackageManifest->basePath);
        $this->assertSame(
            Path::join(config('autodiscovery.base_path'), config('autodiscovery.lock_filename')),
            $laravelPackageManifest->getLockFilePath()
        );
    }
}