<?php

use Goedemiddag\AutodiscoveryLock\Autodiscovery\ResolveResult;
use Illuminate\Support\Collection;

final class ResolveResultTest extends \PHPUnit\Framework\TestCase
{
    public function testWithoutMismatches(): void
    {
        $result = new ResolveResult(
            new Collection(
                []
            ),
            new Collection(
                []
            ),
        );

        // assert that the hasPackagesNotInLock() method returns false and the hasPackagesNotInAutoload() method returns false
        $this->assertFalse($result->hasPackagesNotInLock());
        $this->assertFalse($result->hasPackagesNotInAutoload());

        // assert that the hasNoMismatches() method returns true
        $this->assertTrue($result->hasNoMismatches());
    }

    public function testWithMismatches(): void
    {
        $result = new ResolveResult(
            new Collection(
                [
                    'foo',
                    'bar',
                ]
            ),
            new Collection(
                [
                    'baz',
                    'qux',
                ]
            ),
        );

        // assert that the hasPackagesNotInLock() method returns true and the hasPackagesNotInAutoload() method returns true
        $this->assertTrue($result->hasPackagesNotInLock());
        $this->assertTrue($result->hasPackagesNotInAutoload());

        // assert that the hasNoMismatches() method returns false
        $this->assertFalse($result->hasNoMismatches());
    }
}
