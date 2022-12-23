<?php

namespace Goedemiddag\AutodiscoveryLock\Autodiscovery;

use Illuminate\Support\Collection;

final class ResolveResult
{
    public function __construct(
        private readonly Collection $notInLock,
        private readonly Collection $notInAutoload
    ) {
    }

    public function hasNoMismatches(): bool
    {
        return !$this->hasPackagesNotInAutoload() && !$this->hasPackagesNotInLock();
    }

    public function hasPackagesNotInLock(): bool
    {
        return count($this->notInLock) > 0;
    }

    public function hasPackagesNotInAutoload(): bool
    {
        return count($this->notInAutoload) > 0;
    }

    public function getNotInLock(): Collection
    {
        return $this->notInLock;
    }

    public function getNotInAutoload(): Collection
    {
        return $this->notInAutoload;
    }
}
