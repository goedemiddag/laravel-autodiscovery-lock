<?php

namespace Goedemiddag\AutodiscoveryLock\Commands;

use Goedemiddag\AutodiscoveryLock\Autodiscovery\LaravelPackageManifest;
use Illuminate\Console\Command;
use Illuminate\Foundation\PackageManifest;
use Illuminate\Support\Collection;

class AutodiscoveryPackageLock extends Command
{
    protected $signature = 'autodiscovery:generate-lock';
    protected $description = 'Generate a lock file for all autodiscovered packages';

    private PackageManifest $packageManifest;

    public function __construct(PackageManifest $manifest)
    {
        parent::__construct();

        $this->packageManifest = new LaravelPackageManifest(
            $manifest->files,
            $manifest->basePath,
            $manifest->manifestPath
        );
    }

    public function handle()
    {
        try {
            $collection = $this->packageManifest->collectManifestFromComposerAutoload();

            $this->writeLockfileToDisk($collection);
            $this->info('Autodiscovery lock file generated.');

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Could not generate lock file: ' . $e->getMessage());

            return self::FAILURE;
        }
    }

    private function writeLockfileToDisk(Collection $collection): void
    {
        $this->packageManifest->files->replace(
            $this->packageManifest->getLockFilePath(),
            $collection->toJson(JSON_PRETTY_PRINT)
        );
    }
}
