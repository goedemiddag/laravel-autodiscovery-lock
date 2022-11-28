<?php

namespace Goedemiddag\AutodiscoveryLock\Autodiscovery;

use Exception;
use Illuminate\Foundation\PackageManifest;
use Illuminate\Support\Collection;

class LaravelPackageManifest extends PackageManifest
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
            throw new Exception('No packages found in the manifest.');
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
            throw new Exception('The autodiscovery lock file is not valid JSON.');
        }

        return collect(
            [
               'autodiscovered_packages' =>  $collection
            ]
        );
    }
}
