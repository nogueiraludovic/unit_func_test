<?php

declare(strict_types=1);

namespace Vd\VdCore\Command;

use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

use function file_get_contents;
use function mkdir;
use function putenv;
use function rmdir;

class GenerateHtaccessCommandTest extends FunctionalTestCase
{
    protected string $projectPath;
    protected CommandTester $tester;
    protected array $testExtensionsToLoad = ['vd/vd-core'];

    #[Test]
    public function executeGeneratesHtaccessWhenDistFileExists(): void
    {
        GeneralUtility::writeFile(Environment::getConfigPath() . '/.htaccess.dist', 'RewriteRule ^ %%%ENV:DGNSI_PUBLIC_URL%%%$');

        putenv('DGNSI_PUBLIC_URL=test.example.com');
        putenv('IS_DDEV_PROJECT=1');

        $this->assertSame(Command::SUCCESS, $this->tester->execute([]));
        $this->assertStringContainsString('test\.example\.com', file_get_contents($this->projectPath . '/.htaccess'));
    }

    #[Test]
    public function executeMarksErrorWhenEnvVariableMissing(): void
    {
        GeneralUtility::writeFile(Environment::getConfigPath() . '/.htaccess.dist', 'RewriteRule ^ %%%ENV:DGNSI_BACKEND_URL%%%$');

        putenv('DGNSI_BACKEND_URL');
        putenv('IS_DDEV_PROJECT=1');

        $this->assertSame(Command::SUCCESS, $this->tester->execute([]));
        $this->assertStringContainsString('Missing environment variable:', $this->tester->getDisplay());
        $this->assertSame('RewriteRule ^ $', file_get_contents($this->projectPath . '/.htaccess'));
    }

    #[Test]
    public function executeReplacesNonEnvPlaceholder(): void
    {
        GeneralUtility::writeFile(Environment::getConfigPath() . '/.htaccess.dist', 'RewriteRule ^ %%%FOO%%%$');

        putenv('IS_DDEV_PROJECT=1');

        $this->assertSame(Command::SUCCESS, $this->tester->execute([]));
        $this->assertSame('RewriteRule ^ FOO$', file_get_contents($this->projectPath . '/.htaccess'));
    }

    #[Test]
    public function executeRunsChgrpAndChownWhenNotDdev(): void
    {
        GeneralUtility::writeFile(Environment::getConfigPath() . '/.htaccess.dist', 'RewriteRule ^ %%%FOO%%%$');

        putenv('IS_DDEV_PROJECT=0');

        $this->assertSame(Command::SUCCESS, $this->tester->execute([]));
        $this->assertSame('RewriteRule ^ FOO$', file_get_contents($this->projectPath . '/.htaccess'));
    }

    #[Test]
    public function executeShowsErrorWhenDistFileIsMissing(): void
    {
        $distFile = Environment::getConfigPath() . '/.htaccess.dist';

        if (file_exists($distFile)) {
            unlink($distFile);
        }

        putenv('IS_DDEV_PROJECT=1');

        $this->assertSame(Command::SUCCESS, $this->tester->execute([]));
        $this->assertStringContainsString('Missing file:', $this->tester->getDisplay());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->tester = new CommandTester(new GenerateHtaccessCommand());
        $this->projectPath = Environment::getProjectPath() . '/htdocs';

        mkdir($this->projectPath, 0777, true);
    }

    protected function tearDown(): void
    {
        $distFile = Environment::getConfigPath() . '/.htaccess.dist';

        if (file_exists($distFile)) {
            unlink($distFile);
        }

        rmdir($this->projectPath);

        parent::tearDown();
    }
}
