<?php

declare(strict_types=1);

namespace Vd\VdSite\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Console\Tester\CommandTester;
use TYPO3\CMS\Core\Configuration\ConfigurationManager;
use Vd\VdCore\Command\GenerateLocalConfigurationCommand;
use Vd\VdCore\Configuration\LocalConfigurationBuilder;
use Vd\VdCore\Tests\Unit\Support\AbstractEnvUnitTestCase;

use function array_keys;
use function array_merge;
use function file_put_contents;
use function sys_get_temp_dir;
use function tempnam;
use function uniqid;

final class GenerateLocalConfigurationCommandTest extends AbstractEnvUnitTestCase
{
    private ConfigurationManager&MockObject $configurationManager;
    private array $knownEnvKeys = [
        'TYPO3_FE_DISABLE_NO_CACHE_PARAMETER',
        'TYPO3_INSTALL_DB_PORT',
        'TYPO3_SYS_REVERSE_PROXY_SSL',
        'TYPO3_SYS_SITE_NAME'
    ];
    private LocalConfigurationBuilder $localConfigurationBuilder;
    private string $overlayFile;
    private array $overlayMapping = [
        [['DB', 'Connections', 'Default', 'port'], 'TYPO3_INSTALL_DB_PORT', 'int'],
        [['FE', 'disableNoCacheParameter'], 'TYPO3_FE_DISABLE_NO_CACHE_PARAMETER', 'bool'],
        [['SYS', 'reverseProxySSL'], 'TYPO3_SYS_REVERSE_PROXY_SSL', 'string'],
        [['SYS', 'sitename'], 'TYPO3_SYS_SITE_NAME', 'string']
    ];

    #[DataProvider('failureProvider'), Test]
    public function checkExecuteReturnsFailureAndDoesNotWriteLocalConfiguration(
        ?string $configurationFile,
        string $expected
    ): void {
        $this->configurationManager->expects(self::never())->method('writeLocalConfiguration');

        $command = new CommandTester(
            new GenerateLocalConfigurationCommand(
                $this->localConfigurationBuilder,
                $this->configurationManager,
                $configurationFile
            )
        );
        $exitCode = $command->execute([]);

        self::assertSame(1, $exitCode);
        self::assertStringContainsString($expected, $command->getDisplay());

        $this->deleteTempFile($configurationFile);
    }

    #[DataProvider('successProvider'), Test]
    public function checkExecuteWritesExpectedConfiguration(array $configuration, array $env, array $expected): void
    {
        $this->setEnvPairs($env);
        $this->configurationManager
            ->expects(self::once())
            ->method('writeLocalConfiguration')
            ->with(self::callback(function (array $written) use ($expected): bool {
                foreach ($expected as $path => $value) {
                    $this->assertSame($value, $this->getByDotPath($written, $path));
                }

                return true;
            }));

        $configurationFile = $this->createTempFile($configuration, 'typo3-dist-');
        $command = new CommandTester(
            new GenerateLocalConfigurationCommand(
                $this->localConfigurationBuilder,
                $this->configurationManager,
                $configurationFile
            )
        );
        $exitCode = $command->execute([]);

        self::assertSame(0, $exitCode);
        self::assertStringContainsString('Generated:', $command->getDisplay());

        $this->deleteTempFile($configurationFile);
    }

    public static function failureProvider(): array
    {
        $invalidFile = tempnam(sys_get_temp_dir(), 'typo3-dist-invalid-');
        $missingPath = sys_get_temp_dir() . '/typo3-missing-dist-' . uniqid('', true) . '.php';

        if ($invalidFile !== false) {
            file_put_contents($invalidFile, "<?php\nreturn 'nope';\n");
        }

        return [
            'Invalid dist file (not array)' => [
                $invalidFile ?: $missingPath,
                'Invalid dist configuration:'
            ],
            'Missing dist file' => [
                $missingPath,
                'Missing file:'
            ]
        ];
    }

    public static function successProvider(): array
    {
        return [
            'Env overrides applied' => [
                [
                    'DB' => ['Connections' => ['Default' => ['port' => 3306]]],
                    'FE' => [
                        'disableNoCacheParameter' => false
                    ],
                    'SYS' => [
                        'reverseProxySSL' => '',
                        'sitename' => 'Dist'
                    ]
                ],
                [
                    'TYPO3_FE_DISABLE_NO_CACHE_PARAMETER' => 'true',
                    'TYPO3_INSTALL_DB_PORT' => '3310',
                    'TYPO3_SYS_REVERSE_PROXY_SSL' => '*',
                    'TYPO3_SYS_SITE_NAME' => 'Env Site'
                ],
                [
                    'DB.Connections.Default.port' => 3310,
                    'FE.disableNoCacheParameter' => true,
                    'SYS.reverseProxySSL' => '*',
                    'SYS.sitename' => 'Env Site'
                ],
            ],
            'Missing env keeps dist' => [
                [
                    'DB' => ['Connections' => ['Default' => ['port' => 3306]]],
                    'SYS' => ['sitename' => 'Dist']
                ],
                [],
                [
                    'DB.Connections.Default.port' => 3306,
                    'SYS.sitename' => 'Dist'
                ]
            ]
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->backupEnv = $this->backupEnv(array_merge($this->knownEnvKeys, ['IS_DDEV_PROJECT']));
        $this->unsetEnvKeys(array_keys($this->backupEnv));
        $this->setEnvPairs(['IS_DDEV_PROJECT' => '1']);
        $this->configurationManager = $this->createMock(ConfigurationManager::class);
        $this->overlayFile = $this->createTempFile($this->overlayMapping, 'typo3-overlay-');
        $this->localConfigurationBuilder = new LocalConfigurationBuilder($this->overlayFile);
    }

    protected function tearDown(): void
    {
        $this->deleteTempFile($this->overlayFile);
        $this->restoreEnv($this->backupEnv);

        parent::tearDown();
    }
}