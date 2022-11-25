<?php

namespace Goedemiddag\AutodiscoveryLock\Commands;

use Goedemiddag\AutodiscoveryLock\Autodiscovery\LaravelPackageManifest;
use Illuminate\Console\Command;
use Illuminate\Foundation\PackageManifest;
use Illuminate\Support\Collection;

class AutodiscoveryPackageLockVerify extends Command
{
    protected $signature = 'autodiscovery:verify-lock';
    protected $description = 'verify that the lockfile on disk is the same as the autodiscovered packages';

    private const PACKAGE_LOCK_FILE = 'autodiscovery.lock';

    private PackageManifest $packageManifest;
    private Collection  $warningMessages;

    public function __construct(PackageManifest $manifest)
    {
        parent::__construct();

        $this->packageManifest = new LaravelPackageManifest(
            $manifest->files,
            $manifest->basePath,
            $manifest->manifestPath
        );
        $this->warningMessages = new Collection();
    }

    public function handle()
    {
        try {
            $lockCollection = $this->packageManifest->collectManifestFromLock();
            $autoloadCollection = $this->packageManifest->collectManifestFromComposerAutoload();

            $this->checkIfAutodiscoveredPackagesAreInLockfile($autoloadCollection, $lockCollection);
            $this->checkIfLockedPackagesAreInAutoDiscovery($autoloadCollection, $lockCollection);

            if (count($this->warningMessages) > 0) {
                $this->warn('The lock file is not up to date with the autodiscovered packages.');
                $this->line('Run `php artisan autodiscovery:generate-lock` to update the lock file.');
                $this->line('The following packages are not in sync:');

                $this->warningMessages->each(function ($message) {
                    $this->warn($message);
                });

                throw new \Exception('A missmatch between the lock file and the autodiscovered packages was found.');
            } else {
                $this->info('The lock file is up to date with the autodiscovered packages.');
            }
            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('The lockfile  file did not verify: ' . $e->getMessage());

            return self::FAILURE;
        }
    }

    private function checkIfLockedPackagesAreInAutoDiscovery(Collection $lockCollection, Collection $autoloadCollection): void
    {
        $warningMessages = $this->warningMessages;
        $lockCollection->flatten()->diff($autoloadCollection->flatten())->each(function ($item) use ($warningMessages) {
            $warningMessages[] = $item . ' is found in the lock file but not in the autodiscovered packages.';
        });
    }

    private function checkIfAutodiscoveredPackagesAreInLockfile(Collection $lockCollection, Collection $autoloadCollection): void
    {
        $warningMessages = $this->warningMessages;
        $autoloadCollection->flatten()->diff($lockCollection->flatten())->each(function ($item) use ($warningMessages) {
            $warningMessages[] = $item . ' is found in the autodiscovered packages but not in the lock file.';
        });
    }
}
