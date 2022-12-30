<?php

namespace Goedemiddag\AutodiscoveryLock\Tests\Autodiscovery;

use Goedemiddag\AutodiscoveryLock\Autodiscovery\AutodiscoveryLockResolver;
use Goedemiddag\AutodiscoveryLock\Tests\TestCase;
use Illuminate\Support\Collection;

final class AutoDiscoveryLockResolverTest extends TestCase
{
    public function testGetClassesInLockfileMissingFromAutoload(): void
    {
        $resolver = new AutodiscoveryLockResolver();

        $lockCollection = new Collection(
            [
                'foo',
                'bar',
            ]
        );

        $autoloadCollection = new Collection(
            [
                'baz',
                'qux',
            ]
        );

        $result = $resolver->getClassesInLockfileMissingFromAutoload($lockCollection, $autoloadCollection);

        $this->assertEquals(
            new Collection(
                [
                    'foo',
                    'bar',
                ]
            ),
            $result
        );
    }

    public function testGetClassesInAutoloadMissingFromLockfile(): void
    {
        $resolver = new AutodiscoveryLockResolver();

        $lockCollection = new Collection(
            [
                'foo',
                'bar',
            ]
        );

        $autoloadCollection = new Collection(
            [
                'baz',
                'qux',
            ]
        );

        $result = $resolver->getClassesInAutoloadMissingFromLockfile($lockCollection, $autoloadCollection);

        $this->assertEquals(
            new Collection(
                [
                    'baz',
                    'qux',
                ]
            ),
            $result
        );
    }

    public function testWithNothingMissing(): void
    {
        $resolver = new AutodiscoveryLockResolver();

        $lockCollection = new Collection(
            [
                'foo',
                'bar',
            ]
        );

        $autoloadCollection = new Collection(
            [
                'foo',
                'bar',
            ]
        );

        // assert no mismatches are found from Lockfile
        $this->assertEquals(
            new Collection([]),
            $resolver->getClassesInAutoloadMissingFromLockfile($lockCollection, $autoloadCollection)
        );

        // assert no mismatches are found from Autoload
        $this->assertEquals(
            new Collection([]),
            $resolver->getClassesInLockfileMissingFromAutoload($lockCollection, $autoloadCollection)
        );
    }
}
