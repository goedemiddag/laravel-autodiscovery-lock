<?php

namespace Goedemiddag\AutodiscoveryLock\Commands;

use Goedemiddag\AutodiscoveryLock\Autodiscovery\AutodiscoveryLockResolver;
use Goedemiddag\AutodiscoveryLock\Autodiscovery\LaravelPackageManifest;
use Goedemiddag\AutodiscoveryLock\Factories\LaravelPackageManifestFactory;
use Illuminate\Console\Command;
use Illuminate\Foundation\PackageManifest;
use Illuminate\Support\Collection;

class AutodiscoveryPackageLockVerify extends Command
{
    protected $signature = 'autodiscovery:verify-lock';
    protected $description = 'verify that the lockfile on disk is the same as the autodiscovered packages';

    private readonly LaravelPackageManifest $packageManifest;

    public function __construct(
        PackageManifest $manifest,
        LaravelPackageManifestFactory $laravelPackageManifestFactory,
        private readonly AutodiscoveryLockResolver $resolver
    ) {
        parent::__construct();

        $this->packageManifest = $laravelPackageManifestFactory->build($manifest);
    }

    public function handle(): int
    {
        try {
            $result = $this->resolver->resolve($this->packageManifest);

            if ($result->hasNoMismatches()) {
                $this->info('The lock file is up to date with the autodiscovered packages.');

                return self::SUCCESS;
            }

            $this->warn('The lock file is not up to date with the autodiscovered packages.');
            $this->displayNotInAutoloadMessages($result->getNotInAutoload());
            $this->displayNotInLockMessages($result->getNotInLock());
            $this->error('A mismatch between the lock file and the autodiscovered packages was found.');
        } catch (\Exception $e) {
            $this->error('Could not verify lock file: ' . $e->getMessage());
        }

        return self::FAILURE;
    }

    private function displayNotInLockMessages(Collection $errorMessages): void
    {
        if ($errorMessages->isNotEmpty()) {
            $this->warn('The following classes are in the lock file but not in the autodiscovered packages:');

            $errorMessages->each(function ($item) {
                $this->line('- ' . $item);
            });
        }
    }

    private function displayNotInAutoloadMessages(Collection $errorMessages): void
    {
        if ($errorMessages->isNotEmpty()) {
            $this->warn('The following classes are in the autodiscovered packages but not in the lock file:');

            $errorMessages->each(function ($item) {
                $this->line('- ' . $item);
            });
        }
    }
}
