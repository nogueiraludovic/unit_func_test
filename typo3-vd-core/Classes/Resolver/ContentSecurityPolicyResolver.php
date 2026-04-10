<?php

declare(strict_types=1);

namespace Vd\VdCore\Resolver;

use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Package\PackageManager;

use function array_flip;
use function file_exists;
use function is_array;
use function sha1;
use function var_export;

class ContentSecurityPolicyResolver
{
    public function __construct(protected FrontendInterface $cache, protected PackageManager $packageManager)
    {
    }

    public function resolve(): array
    {
        $cacheIdentifier = $this->getCacheIdentifier();

        if ($this->cache->has($cacheIdentifier) === true) {
            /** @noinspection PhpPossiblePolymorphicInvocationInspection */
            return $this->cache->require($cacheIdentifier);
        }

        $policies = $this->loadConfiguration();

        $this->cache->set($cacheIdentifier, 'return ' . var_export($policies, true) . ';');

        return $policies;
    }

    protected function getCacheIdentifier(): string
    {
        return 'csp_' . sha1((new Typo3Version()) . Environment::getProjectPath());
    }

    protected function loadConfiguration(): array
    {
        $allPolicies = [];
        $packages = $this->packageManager->getActivePackages();

        foreach ($packages as $package) {
            $packageConfiguration = $package->getPackagePath() . 'Configuration/CSP.php';

            if (file_exists($packageConfiguration) === true) {
                $policiesInPackage = require $packageConfiguration;

                if (is_array($policiesInPackage) === true) {
                    $allPolicies[] = $policiesInPackage;
                }
            }
        }

        return $this->mergePolicies($allPolicies);
    }

    protected function mergePolicies(array $allPolicies): array
    {
        $merged = [];

        foreach ($allPolicies as $policy) {
            foreach ($policy as $directive => $values) {
                if (is_array($values) === false || $values === []) {
                    $merged[$directive] = $values;

                    continue;
                }

                foreach ($values as $subKey => $subValues) {
                    if (is_array($subValues) === false || $subValues === []) {
                        $merged[$directive][$subKey] = $subValues;

                        continue;
                    }

                    $merged[$directive][$subKey] ??= [];
                    $seen = array_flip($merged[$directive][$subKey]);

                    foreach ($subValues as $value) {
                        if (isset($seen[$value]) === true) {
                            continue;
                        }

                        $merged[$directive][$subKey][] = $value;
                        $seen[$value] = true;
                    }
                }
            }
        }

        return $merged;
    }
}
