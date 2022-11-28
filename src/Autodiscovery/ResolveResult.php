<?php

namespace Goedemiddag\AutodiscoveryLock\Autodiscovery;

use Illuminate\Support\Collection;

final class ResolveResult
{
    private Collection $notInLock;

    private Collection $notInAutoload;

    public function __construct(
        Collection $notInLock,
        Collection $notInAutoload,
    ) {
        $this->notInLock = $notInLock;
        $this->notInAutoload = $notInAutoload;
    }

    public function hasNoMismatches(): bool
    {
        return $this->hasPackagesNotInAutoload() === false && $this->hasPackagesNotInLock() === false;
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
