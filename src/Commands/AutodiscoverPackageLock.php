<?php

namespace Goedemiddag\AutodiscoveryLock\Commands;

use Goedemiddag\AutodiscoveryLock\Autodiscovery\LaravelPackageManifest;
use Illuminate\Console\Command;
use Illuminate\Foundation\PackageManifest;

class AutodiscoverPackageLock extends Command {
    const PACKAGE_LOCK_FILE = 'autodisovery.lock';

    protected $signature = 'autodiscovery:lock-generate';
    protected $description = 'Generate a lock file for all autodiscovered packages';
    private $packageManifest;

    public function __construct(PackageManifest $manifest) {
        $this->packageManifest = new LaravelPackageManifest($manifest->files, $manifest->basePath, $manifest->manifestPath);

        parent::__construct();
    }

    public function handle() {

        try {
            $manifest = $this->packageManifest->getManifest();

            if (is_iterable($manifest) === false || count($manifest)  === 0) {
                throw new \Exception('No packages found in the manifest.');
            }
        } catch (\Exception $e) {
            $this->error('Could not generate lock file: ' . $e->getMessage());

            return 1;
        }

        $collection = collect(
            [
                'autodiscovered_packages' => $this->packageManifest->getManifest(),
            ]
		);

        $this->packageManifest->files->replace(
            $this->packageManifest->basePath . DIRECTORY_SEPARATOR . self::PACKAGE_LOCK_FILE,
            $collection->toJson(JSON_PRETTY_PRINT)
        );

        $this->info('Autodiscovery lock file generated.');

        return 0;
    }
}
