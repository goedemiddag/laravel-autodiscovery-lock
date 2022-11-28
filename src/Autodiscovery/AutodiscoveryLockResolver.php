<?php

namespace Goedemiddag\AutodiscoveryLock\Autodiscovery;

use Illuminate\Support\Collection;

final class AutodiscoveryLockResolver
{
    public function resolve(LaravelPackageManifest $manifest): ResolveResult
    {
        $autoload = $manifest->collectManifestFromComposerAutoload();
        $lock = $manifest->collectManifestFromLock();

        return new ResolveResult(
            $this->getClassesInAutoloadMissingFromLockfile($lock, $autoload),
            $this->getClassesInLockfileMissingFromAutoload($lock, $autoload),
        );
    }

    public function getClassesInLockfileMissingFromAutoload(
        Collection $lockCollection,
        Collection $autoloadCollection,
    ): Collection {
        return $lockCollection->flatten()->diff($autoloadCollection->flatten());
    }

    public function getClassesInAutoloadMissingFromLockfile(
        Collection $lockCollection,
        Collection $autoloadCollection,
    ): Collection {
        return $autoloadCollection->flatten()->diff($lockCollection->flatten());
    }
}
