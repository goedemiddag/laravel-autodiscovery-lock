<?php

namespace Goedemiddag\AutodiscoveryLock\Commands;

use Goedemiddag\AutodiscoveryLock\Autodiscovery\AutodiscoveryLockResolver;
use Goedemiddag\AutodiscoveryLock\Autodiscovery\LaravelPackageManifest;
use Illuminate\Console\Command;
use Illuminate\Foundation\PackageManifest;
use Illuminate\Support\Collection;

class AutodiscoveryPackageLockVerify extends Command
{
    protected $signature = 'autodiscovery:verify-lock';
    protected $description = 'verify that the lockfile on disk is the same as the autodiscovered packages';

    private LaravelPackageManifest $packageManifest;
    private AutodiscoveryLockResolver $resolver;
    private Collection  $warningMessages;

    public function __construct(PackageManifest $manifest, AutodiscoveryLockResolver $resolver)
    {
        parent::__construct();

        $this->packageManifest = new LaravelPackageManifest(
            $manifest->files,
            $manifest->basePath,
            $manifest->manifestPath
        );
        $this->resolver = $resolver;
        $this->warningMessages = new Collection();
    }

    public function handle()
    {
        try {
            $errorMessages = $this->resolver->resolve($this->packageManifest);

            if ($errorMessages->isEmpty()) {
                $this->info('The lock file is up to date with the autodiscovered packages.');

                return self::SUCCESS;
            }

            $this->warn('The lock file is not up to date with the autodiscovered packages.');
            $this->line('Run `php artisan autodiscovery:generate-lock` to update the lock file.');
            $this->line('The following packages are not in sync:');

            $this->warningMessages->each(function ($message) {
                $this->warn($message);
            });

            throw new \Exception('A mismatch between the lock file and the autodiscovered packages was found.');

        } catch (\Exception $e) {
            $this->error('The lockfile  file did not verify: ' . $e->getMessage());

            return self::FAILURE;
        }
    }
}
