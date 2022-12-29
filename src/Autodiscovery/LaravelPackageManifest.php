<?php

namespace Goedemiddag\AutodiscoveryLock\Autodiscovery;

use Illuminate\Foundation\PackageManifest;
use Illuminate\Support\Collection;
use InvalidLockException;
use InvalidManifestException;
use Symfony\Component\Filesystem\Path;

class LaravelPackageManifest extends PackageManifest
{
    public function fetchManifest(): Collection
    {
        return collect(parent::getManifest());
    }

    public function getLockFilePath(): string
    {
        /** @var string $fileName */
        $fileName = config('autodiscovery.lock_filename');

        return Path::join($this->basePath, $fileName);
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
