<?php

declare(strict_types=1);

namespace Vd\VdCore\Tests\Unit\Configuration;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Vd\VdCore\Configuration\LocalConfigurationBuilder;
use Vd\VdCore\Tests\Unit\Support\AbstractEnvUnitTestCase;

use function array_keys;

final class LocalConfigurationBuilderTest extends AbstractEnvUnitTestCase
{
    private array $knownEnvKeys = [
        'TYPO3_FE_DISABLE_NO_CACHE_PARAMETER',
        'TYPO3_INSTALL_DB_PORT',
        'TYPO3_SYS_REVERSE_PROXY_SSL',
        'TYPO3_SYS_SITE_NAME'
    ];
    private string $overlayFile;
    private array $overlayMapping = [
        [['DB', 'Connections', 'Default', 'port'], 'TYPO3_INSTALL_DB_PORT', 'int'],
        [['FE', 'disableNoCacheParameter'], 'TYPO3_FE_DISABLE_NO_CACHE_PARAMETER', 'bool'],
        [['SYS', 'reverseProxySSL'], 'TYPO3_SYS_REVERSE_PROXY_SSL', 'string'],
        [['SYS', 'sitename'], 'TYPO3_SYS_SITE_NAME', 'string']
    ];
    private LocalConfigurationBuilder $subject;

    public static function buildProvider(): array
    {
        return [
            'Bool override false' => [
                [
                    'FE' => ['disableNoCacheParameter' => true]
                ],
                [
                    'TYPO3_FE_DISABLE_NO_CACHE_PARAMETER' => '0'
                ],
                [
                    'FE.disableNoCacheParameter' => false
                ]
            ],
            'Bool override true' => [
                [
                    'FE' => ['disableNoCacheParameter' => false]
                ],
                [
                    'TYPO3_FE_DISABLE_NO_CACHE_PARAMETER' => 'true'
                ],
                [
                    'FE.disableNoCacheParameter' => true
                ]
            ],
            'Empty env does not override bool' => [
                [
                    'FE' => ['disableNoCacheParameter' => true]
                ],
                [
                    'TYPO3_FE_DISABLE_NO_CACHE_PARAMETER' => ''
                ],
                [
                    'FE.disableNoCacheParameter' => true
                ]
            ],
            'Empty env does not override string' => [
                [
                    'SYS' => ['sitename' => 'Dist']
                ],
                [
                    'TYPO3_SYS_SITE_NAME' => ''
                ],
                [
                    'SYS.sitename' => 'Dist'
                ]
            ],
            'Int override' => [
                [
                    'DB' => ['Connections' => ['Default' => ['port' => 3306]]]
                ],
                [
                    'TYPO3_INSTALL_DB_PORT' => '3307'
                ],
                [
                    'DB.Connections.Default.port' => 3307
                ]
            ],
            'Missing env keeps dist' => [
                [
                    'SYS' => ['sitename' => 'Dist']
                ],
                [],
                [
                    'SYS.sitename' => 'Dist'
                ]
            ],
            '"reverseProxySSL" wildcard string' => [
                [
                    'SYS' => ['reverseProxySSL' => '']
                ],
                [
                    'TYPO3_SYS_REVERSE_PROXY_SSL' => '*'
                ],
                [
                    'SYS.reverseProxySSL' => '*'
                ]
            ],
            'String override sitename' => [
                [
                    'SYS' => ['sitename' => 'Dist']
                ],
                [
                    'TYPO3_SYS_SITE_NAME' => 'Env Site'
                ],
                [
                    'SYS.sitename' => 'Env Site'
                ]
            ]
        ];
    }

    #[DataProvider('buildProvider'), Test]
    public function checkBuildBehavesAsExpected(array $configuration, array $env, array $expected): void
    {
        $this->setEnvPairs($env);

        $final = $this->subject->build($configuration);

        foreach ($expected as $path => $value) {
            self::assertSame($value, $this->getByDotPath($final, $path));
        }
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->backupEnv = $this->backupEnv($this->knownEnvKeys);
        $this->overlayFile = $this->createTempFile($this->overlayMapping, 'typo3-overlay-');
        $this->subject = new LocalConfigurationBuilder($this->overlayFile);
        $this->unsetEnvKeys(array_keys($this->backupEnv));
    }

    protected function tearDown(): void
    {
        $this->deleteTempFile($this->overlayFile);
        $this->restoreEnv($this->backupEnv);

        parent::tearDown();
    }
}
