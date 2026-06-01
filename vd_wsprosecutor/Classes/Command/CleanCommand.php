<?php

declare(strict_types=1);

namespace Vd\VdWsprosecutor\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Vd\VdWsprosecutor\DataHandling\DataHandlerTrait;
use Vd\VdWsprosecutor\Domain\Repository\OfficeHourRepository;

use function count;

class CleanCommand extends Command
{
    use DataHandlerTrait;

    protected OfficeHourRepository $officeHourRepository;

    public function __construct(string $name = null)
    {
        parent::__construct($name);

        $this->officeHourRepository = GeneralUtility::makeInstance(OfficeHourRepository::class);
    }

    protected function configure(): void
    {
        $this->setDescription('Delete outdated prosecutor\'s office hours.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $cmd = [];
        $outdatedRecords = $this->officeHourRepository->fetchOutdatedRecords();

        foreach ($outdatedRecords as $outdatedRecord) {
            $cmd['tx_vdwsprosecutor_domain_model_officehour'][$outdatedRecord]['delete'] = 1;
        }

        if (count($cmd) === 0) {
            return Command::SUCCESS;
        }

        $this->processDataHandling($cmd);

        return Command::SUCCESS;
    }
}
