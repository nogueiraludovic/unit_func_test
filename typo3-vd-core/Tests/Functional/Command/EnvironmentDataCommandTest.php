<?php

declare(strict_types=1);

namespace Vd\VdCore\Command;

use Composer\InstalledVersions;
use PHPUnit\Framework\Attributes\Test;
use ReflectionClass;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use TYPO3\CMS\Core\Registry;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class EnvironmentDataCommandTest extends FunctionalTestCase
{
    protected EnvironmentDataCommand $command;
    protected Registry $registry;
    protected array $testExtensionsToLoad = ['vd/vd-core'];

    #[Test]
    public function executeRunsThroughCommandTester(): void
    {
        $this->assertSame(Command::SUCCESS, (new CommandTester($this->command))->execute(['--debug' => true]));
        $this->assertNotNull($this->registry->get('vd_core', 'prodDatabaseDate'));
        $this->assertNotNull($this->registry->get('vd_core', 'prodProjectVersion'));
    }

    #[Test]
    public function executeReturnsEarlyWhenContextIsNotProductionAndDebugIsFalse(): void
    {
        $this->assertSame(Command::SUCCESS, (new CommandTester($this->command))->execute(['--debug' => 0]));
        $this->assertNull($this->registry->get('vd_core', 'prodDatabaseDate'));
        $this->assertNull($this->registry->get('vd_core', 'prodProjectVersion'));
    }

    #[Test]
    public function executeReturnsEarlyWhenPrettyVersionIsEmpty(): void
    {
        $installedProperty = (new ReflectionClass(InstalledVersions::class))->getProperty('installed');
        $installedProperty->setAccessible(true);
        $installedProperty->setValue(null, [
            [
                'root' => [
                    'pretty_version' => ''
                ]
            ]
        ]);
        $this->assertSame(Command::SUCCESS, (new CommandTester($this->command))->execute(['--debug' => true]));
        $this->assertNull($this->registry->get('vd_core', 'prodDatabaseDate'));
        $this->assertNull($this->registry->get('vd_core', 'prodProjectVersion'));
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->registry = $this->get(Registry::class);
        $this->command = new EnvironmentDataCommand('env', $this->registry);
    }
}
