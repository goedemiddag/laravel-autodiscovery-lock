<?php

namespace Goedemiddag\AutodiscoveryLock\Autodiscovery;

use Illuminate\Foundation\PackageManifest;
use Illuminate\Support\Collection;
use InvalidLockException;
use InvalidManifestException;

class LaravelPackageManifest extends PackageManifest
{
    final public const PACKAGE_LOCK_FILE = 'autodiscovery.lock';

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
            throw InvalidManifestException::autoDiscoveryIsEmpty();
        }

        return collect([
            'autodiscovered_packages' => $manifest,
        ]);
    }

    public function collectManifestFromLock(): Collection
    {
        $lockFile = $this->files->get($this->getLockFilePath());

        $collection = json_decode($lockFile, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw InvalidLockException::lockIsInvalid();
        }

        return collect(
            [
               'autodiscovered_packages' =>  $collection
            ]
        );
    }
}
