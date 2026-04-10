<?php

declare(strict_types=1);

namespace Vd\VdCore\Tests\Functional\Command;

use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use TYPO3\CMS\Core\Configuration\ConfigurationManager;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;
use Vd\VdCore\Command\GenerateLocalConfigurationCommand;
use Vd\VdCore\Configuration\LocalConfigurationBuilder;

use function file_put_contents;
use function is_file;

final class GenerateLocalConfigurationCommandTest extends FunctionalTestCase
{
    #[Test]
    public function executeGeneratesLocalConfiguration(): void
    {
        $path = Environment::getConfigPath() . '/LocalConfiguration.dist.php';
        file_put_contents($path, '<?php return ["foo" => "bar"];');

        $command = new GenerateLocalConfigurationCommand(
            new LocalConfigurationBuilder(),
            new ConfigurationManager(),
            $path
        );

        $command->run(new ArrayInput([]), new BufferedOutput());

        $this->assertTrue(is_file(Environment::getPublicPath() . '/typo3conf/LocalConfiguration.php'));
    }
}
