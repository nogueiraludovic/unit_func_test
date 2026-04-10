<?php

declare(strict_types=1);

namespace Vd\VdCore\Tests\Unit\Support;

use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

use function array_key_exists;
use function explode;
use function file_put_contents;
use function getenv;
use function is_array;
use function putenv;
use function sys_get_temp_dir;
use function tempnam;
use function unlink;
use function var_export;

abstract class AbstractEnvUnitTestCase extends UnitTestCase
{
    protected array $backupEnv = [];

    protected function backupEnv(array $keys): array
    {
        $backupEnv = [];

        foreach ($keys as $key) {
            $backupEnv[$key] = getenv($key);
        }

        return $backupEnv;
    }

    protected function createTempFile(array $configuration, string $prefix): string
    {
        $file = tempnam(sys_get_temp_dir(), $prefix);

        self::assertNotFalse($file);

        $php = "<?php\ndeclare(strict_types=1);\nreturn " . var_export($configuration, true) . ";\n";

        file_put_contents($file, $php);

        return $file;
    }

    protected function deleteTempFile(?string $file): void
    {
        if ($file === null) {
            return;
        }

        @unlink($file);
    }

    protected function getByDotPath(array $array, string $dotPath): mixed
    {
        $node = $array;
        $parts = explode('.', $dotPath);

        foreach ($parts as $part) {
            if (is_array($node) === false || array_key_exists($part, $node) === false) {
                self::fail('Missing path: ' . $dotPath);
            }

            $node = $node[$part];
        }

        return $node;
    }

    protected function restoreEnv(array $backupEnv): void
    {
        foreach ($backupEnv as $key => $value) {
            if ($value === false) {
                putenv($key);
            } else {
                putenv($key . '=' . $value);
            }
        }
    }

    protected function setEnvPairs(array $env): void
    {
        foreach ($env as $key => $value) {
            putenv($key . '=' . $value);
        }
    }

    protected function unsetEnvKeys(array $keys): void
    {
        foreach ($keys as $key) {
            putenv($key);
        }
    }
}
