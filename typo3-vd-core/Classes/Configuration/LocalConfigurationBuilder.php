<?php

declare(strict_types=1);

namespace Vd\VdCore\Configuration;

use TYPO3\CMS\Core\Core\Environment;

use function count;
use function filter_var;
use function getenv;
use function is_array;
use function is_file;
use function trim;

use const FILTER_NULL_ON_FAILURE;
use const FILTER_VALIDATE_BOOLEAN;

final readonly class LocalConfigurationBuilder
{
    public function __construct(private ?string $configurationFile = null)
    {
    }

    public function build(array $configuration): array
    {
        $overrides = $this->buildOverrides();

        foreach ($overrides as [$path, $value]) {
            if ($value === null) {
                continue;
            }

            $this->setPath($configuration, $path, $value);
        }

        return $configuration;
    }

    private function buildOverrides(): array
    {
        $configurationFile = $this->configurationFile
            ?? (Environment::getConfigPath() . '/LocalConfiguration.overlay.php');

        if (is_file($configurationFile) === false) {
            return [];
        }

        $configuration = require $configurationFile;

        if (is_array($configuration) === false) {
            return [];
        }

        $overrides = [];

        foreach ($configuration as $row) {
            if (is_array($row) === false || count($row) !== 3) {
                continue;
            }

            [$path, $key, $type] = $row;

            $overrides[] = [
                $path,
                match ($type) {
                    'bool' => $this->envBool((string)$key),
                    'int' => $this->envInt((string)$key),
                    default => $this->envString((string)$key)
                }
            ];
        }

        return $overrides;
    }

    private function envBool(string $key): ?bool
    {
        $value = getenv($key);

        if ($value === false) {
            return null;
        }

        $value = trim((string)$value);

        if ($value === '') {
            return null;
        }

        return filter_var($value, FILTER_VALIDATE_BOOLEAN, ['flags' => FILTER_NULL_ON_FAILURE]);
    }

    private function envInt(string $key): ?int
    {
        $value = getenv($key);

        if ($value === false) {
            return null;
        }

        $value = trim((string)$value);

        return $value === '' ? null : (int)$value;
    }

    private function envString(string $key): ?string
    {
        $value = getenv($key);

        if ($value === false) {
            return null;
        }

        $value = trim((string)$value);

        return $value === '' ? null : $value;
    }

    private function setPath(array &$configuration, array $path, mixed $value): void
    {
        $lastIndex = count($path) - 1;
        $node = & $configuration;

        foreach ($path as $index => $segment) {
            if ($index === $lastIndex) {
                $node[$segment] = $value;

                return;
            }

            $node[$segment] ??= [];

            if (is_array($node[$segment]) === false) {
                $node[$segment] = [];
            }

            $node = & $node[$segment];
        }
    }
}
