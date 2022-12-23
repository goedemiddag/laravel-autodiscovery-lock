<?php

use Illuminate\Support\Collection;

final class AutoDiscoveryLockResolverTest extends \PHPUnit\Framework\TestCase
{
    public function testClassesMissingFromAutoload()
    {
        $lock = new Collection([
            'App\\Providers\\AppServiceProvider',
            'App\\Providers\\EventServiceProvider',
            'App\\Providers\\RouteServiceProvider',
        ]);

        $autoload = new Collection([
            'App\\Providers\\AppServiceProvider',
            'App\\Providers\\EventServiceProvider',
        ]);

        $resolver = new AutodiscoveryLockResolver();

        $result = $resolver->resolve($lock, $autoload);

        $this->assertTrue($result->hasPackagesNotInAutoload());
        $this->assertFalse($result->hasPackagesNotInLock());
        $this->assertEquals(
            new Collection([
                'App\\Providers\\RouteServiceProvider',
            ]),
            $result->getNotInAutoload()
        );
    }

    public function testClassesMissingFromLock()
    {
        $lock = new Collection([
            'App\\Providers\\AppServiceProvider',
            'App\\Providers\\EventServiceProvider',
        ]);

        $autoload = new Collection([
            'App\\Providers\\AppServiceProvider',
            'App\\Providers\\EventServiceProvider',
            'App\\Providers\\RouteServiceProvider',
        ]);

        $resolver = new AutodiscoveryLockResolver();

        $result = $resolver->resolve($lock, $autoload);

        $this->assertFalse($result->hasPackagesNotInAutoload());
        $this->assertTrue($result->hasPackagesNotInLock());
        $this->assertEquals(
            new Collection([
                'App\\Providers\\RouteServiceProvider',
            ]),
            $result->getNotInLock()
        );
    }

    public function testNoMismatches()
    {
        $lock = new Collection([
            'App\\Providers\\AppServiceProvider',
            'App\\Providers\\EventServiceProvider',
        ]);

        $autoload = new Collection([
            'App\\Providers\\AppServiceProvider',
            'App\\Providers\\EventServiceProvider',
        ]);

        $resolver = new AutodiscoveryLockResolver();

        $result = $resolver->resolve($lock, $autoload);

        $this->assertFalse($result->hasPackagesNotInAutoload());
        $this->assertFalse($result->hasPackagesNotInLock());
        $this->assertTrue($result->hasNoMismatches());
    }
}
