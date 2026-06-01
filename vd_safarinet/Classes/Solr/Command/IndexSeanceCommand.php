<?php

declare(strict_types=1);

namespace Vd\VdSafarinet\Solr\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Vd\VdSafarinet\Solr\Provider\SeanceProvider;

class IndexSeanceCommand extends AbstractCommand
{
    protected function configure(): void
    {
        parent::configure();

        $this->setDescription('Command to index SIEL meetings in Solr.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // @extensionScannerIgnoreLine
        $this->index(SeanceProvider::class, $input->getOptions());

        return Command::SUCCESS;
    }
}
