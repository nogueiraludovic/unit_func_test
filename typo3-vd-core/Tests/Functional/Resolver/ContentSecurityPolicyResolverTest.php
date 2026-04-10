<?php

declare(strict_types=1);

namespace Vd\VdCore\Resolver;

use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Cache\Frontend\PhpFrontend;
use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

final class ContentSecurityPolicyResolverTest extends FunctionalTestCase
{
    protected array $coreExtensionsToLoad = [
        'core'
    ];

    protected array $testExtensionsToLoad = [
        __DIR__ . '/Fixtures/vd_csp',
        __DIR__ . '/Fixtures/vd_csp2',
        __DIR__ . '/Fixtures/vd_csp3'
    ];

    #[Test]
    public function resolveReadsPoliciesFromActivePackagesAndCachesResult(): void
    {
        $cache = ($this->get(CacheManager::class))->getCache('cache_core');

        $this->assertInstanceOf(PhpFrontend::class, $cache);

        $resolver = new ContentSecurityPolicyResolver($cache, $this->get(PackageManager::class));
        $resultFirst = $resolver->resolve();

        $this->assertSame(
            [
                'default-src' => [
                    'self' => 'string'
                ],
                'img-src' => ''
            ],
            $resultFirst
        );

        $this->assertSame($resultFirst, $resolver->resolve());
    }

    #[Test]
    public function mergePoliciesCoversAllBranches(): void
    {
        $this->assertSame(
            [
                'default-src' => [
                    'self' => 'string'
                ],
                'img-src' => ''
            ],
            (
                new ContentSecurityPolicyResolver(
                    ($this->get(CacheManager::class))->getCache('cache_core'),
                    $this->get(PackageManager::class)
                )
            )->resolve()
        );
    }
}
