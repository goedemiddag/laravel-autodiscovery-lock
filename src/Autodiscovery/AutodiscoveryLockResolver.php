<?php

namespace Goedemiddag\AutodiscoveryLock\Autodiscovery;

use Illuminate\Support\Collection;

final class AutodiscoveryLockResolver
{
    public function resolve(LaravelPackageManifest $manifest): Collection
    {
        $mismatches = new Collection();
        $autoload = $manifest->collectManifestFromComposerAutoload();
        $lock = $manifest->collectManifestFromLock();

        $this->resolveAllFilesInAutoloadAreInLock($lock, $autoload, $mismatches);
        $this->resolveAllFilesInLockAreInAutoload($lock, $autoload, $mismatches);

        return $mismatches;
    }

    private function resolveAllFilesInLockAreInAutoload(
        Collection $lockCollection,
        Collection $autoloadCollection,
        Collection $mismatches
    ): void {
        $lockCollection->flatten()->diff($autoloadCollection->flatten())->each(function ($item) use ($mismatches) {
            $mismatches->add($item . ' is found in the lock file but not in the autodiscovered packages.');
        });
    }

    private function resolveAllFilesInAutoloadAreInLock(
        Collection $lockCollection,
        Collection $autoloadCollection,
        Collection $mismatches
    ): void {
        $autoloadCollection->flatten()->diff($lockCollection->flatten())->each(function ($item) use ($mismatches) {
            $mismatches->add($item . ' is found in the autodiscovered packages but not in the lock file.');
        });
    }
}
