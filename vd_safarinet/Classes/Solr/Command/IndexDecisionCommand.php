<?php

declare(strict_types=1);

namespace Vd\VdSafarinet\Solr\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Vd\VdSafarinet\Solr\Provider\DecisionProvider;

class IndexDecisionCommand extends AbstractCommand
{
    protected function configure(): void
    {
        parent::configure();

        $this->setDescription('Command to index SIEL decisions in Solr.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // @extensionScannerIgnoreLine
        $this->index(DecisionProvider::class, $input->getOptions());

        return Command::SUCCESS;
    }
}
