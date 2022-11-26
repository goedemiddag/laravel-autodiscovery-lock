<?php

namespace Goedemiddag\AutodiscoveryLock\Autodiscovery;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;

class LaravelPackageManifest extends \Illuminate\Foundation\PackageManifest
{
    public const PACKAGE_LOCK_FILE = 'autodiscovery.lock';

    public function fetchManifest(): Collection
    {
        return collect(parent::getManifest());
    }

    public function getLockFilePath(): string
    {
        return $this->basePath . DIRECTORY_SEPARATOR . self::PACKAGE_LOCK_FILE;
    }

    public function collectManifestFromComposerAutoload(): Collection
    {
        $manifest = $this->fetchManifest();

        if ($manifest->isEmpty()) {
            throw new \Exception('No packages found in the manifest.');
        }

        return collect([
            'autodiscovered_packages' => $manifest,
        ]);
    }

    public function collectManifestFromLock(): Collection
    {
        $lockFile = $this->files->get($this->getLockFilePath());

        return collect(json_decode($lockFile, true));
    }
}
