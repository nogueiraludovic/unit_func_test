<?php

declare(strict_types=1);

namespace Vd\VdSite\Command\Links;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Service\TypoLinkCodecService;

use function count;
use function in_array;
use function is_array;

abstract class AbstractLinksCommand extends Command implements LoggerAwareInterface
{
    use FlashMessageTrait;
    use LoggerAwareTrait;

    protected TypoLinkCodecService $codecService;
    protected ConnectionPool $connectionPool;
    protected array $fields = [];
    protected array $processedTca = [];
    protected array $records = [];
    protected array $urlFields = ['url'];

    public function __construct(string $name = null)
    {
        parent::__construct($name);

        $this->codecService = GeneralUtility::makeInstance(TypoLinkCodecService::class);
        $this->connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);

        if (($this->logger instanceof LoggerInterface) === false) {
            $this->setLogger(GeneralUtility::makeInstance(LogManager::class)->getLogger(__CLASS__));
        }

        $this->processedTca = $GLOBALS['TCA'];
        $this
            ->mergeColumnsOverridesConfiguration()
            ->setFieldsToProceed()
            ->setRecordsToProceed();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title($this->getDescription());

        $this->proceedRecords();

        return Command::SUCCESS;
    }

    protected function isInputLinkField(array $configuration): bool
    {
        return $configuration['config']['type'] === 'input' && $configuration['config']['renderType'] === 'inputLink';
    }

    protected function isRichTextField(array $configuration): bool
    {
        return (bool)$configuration['config']['enableRichtext'] === true && $configuration['config']['type'] === 'text';
    }

    protected function isUrlField(string $field): bool
    {
        return in_array($field, $this->urlFields);
    }

    abstract protected function proceedRecords(): AbstractLinksCommand;

    abstract protected function setRecordsToProceed(): AbstractLinksCommand;

    private function mergeColumnsOverridesConfiguration(): AbstractLinksCommand
    {
        foreach ($this->processedTca as $table => $tableConfiguration) {
            foreach ($tableConfiguration['types'] as $typeConfiguration) {
                if ($typeConfiguration['columnsOverrides'] === null) {
                    continue;
                }

                foreach ($typeConfiguration['columnsOverrides'] as $field => $fieldConfiguration) {
                    ArrayUtility::mergeRecursiveWithOverrule(
                        $this->processedTca[$table]['columns'][$field],
                        $fieldConfiguration
                    );
                }
            }
        }

        return $this;
    }

    private function setFieldsToProceed(): AbstractLinksCommand
    {
        foreach ($this->processedTca as $table => $tableConfiguration) {
            foreach ($tableConfiguration['columns'] as $field => $fieldConfiguration) {
                if (
                    $this->isInputLinkField($fieldConfiguration) === false
                    && $this->isRichTextField($fieldConfiguration) === false
                    && $this->isUrlField($field) === false
                ) {
                    continue;
                }

                $this->fields[$table][] = $field;
            }

            if (is_array($this->fields[$table]) === true && count($this->fields[$table]) > 0) {
                if ($table === 'tt_content') {
                    $this->fields[$table][] = 'CType';
                }

                $this->fields[$table][] = 'pid';
                $this->fields[$table][] = 'uid';
            }
        }

        return $this;
    }
}
