<?php

namespace Goedemiddag\AutodiscoveryLock\Commands;

use Goedemiddag\AutodiscoveryLock\Autodiscovery\LaravelPackageManifest;
use Illuminate\Console\Command;
use Illuminate\Foundation\PackageManifest;
use Illuminate\Support\Collection;

class AutodiscoverPackageLock extends Command
{
    protected $signature = 'autodiscovery:generate-lock';
    protected $description = 'Generate a lock file for all autodiscovered packages';

    private const PACKAGE_LOCK_FILE = 'autodiscovery.lock';

    private PackageManifest $packageManifest;

    public function __construct(PackageManifest $manifest)
    {
        $this->packageManifest = new LaravelPackageManifest(
            $manifest->files,
            $manifest->basePath,
            $manifest->manifestPath
        );

        parent::__construct();
    }

    public function handle()
    {
        try {
            $collection = $this->collectManifest();
            $this->writeLockfileToDisk($collection);
            $this->info('Autodiscovery lock file generated.');

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Could not generate lock file: ' . $e->getMessage());

            return self::FAILURE;
        }
    }

    private function collectManifest(): Collection
    {
        $manifest = $this->packageManifest->getManifest();

        if (is_iterable($manifest) === false || count($manifest)  === 0) {
            throw new \Exception('No packages found in the manifest.');
        }

        return collect([
                'autodiscovered_packages' => $this->packageManifest->getManifest(),
            ]);
    }

    private function writeLockfileToDisk(Collection $collection): void
    {
        $this->packageManifest->files->replace(
            $this->packageManifest->basePath . DIRECTORY_SEPARATOR . self::PACKAGE_LOCK_FILE,
            $collection->toJson(JSON_PRETTY_PRINT)
        );
    }
}
