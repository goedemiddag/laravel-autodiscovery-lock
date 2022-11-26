<?php

namespace Goedemiddag\AutodiscoveryLock\Autodiscovery;

use Illuminate\Support\Collection;

final class AutodiscoveryLockResolver
{
    public const NOT_IN_LOCK = 'notInLock';
    public const NOT_IN_AUTOLOAD = 'notInAutoload';
    public function resolve(LaravelPackageManifest $manifest): Collection
    {
        $autoload = $manifest->collectManifestFromComposerAutoload();
        $lock = $manifest->collectManifestFromLock();

        return collect(
            [
                self::NOT_IN_LOCK => $this->getClassesInAutoloadMissingFromLockfile($lock, $autoload),
                self::NOT_IN_AUTOLOAD => $this->getClassesInLockfileMissingFromAutoload($lock, $autoload),
            ]
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
