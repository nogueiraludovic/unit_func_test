<?php

declare(strict_types=1);

namespace Vd\VdSite\Command\Sitemap;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class CrawlSitemapCommand extends AbstractSitemapCommand
{
    protected function configure(): void
    {
        $this
            ->addOption(
                'number',
                null,
                InputOption::VALUE_REQUIRED,
                'The number of sitemap items to warmup (0 => all items)',
                5
            )
            ->setDescription('Crawl the sub-sitemap to warm-up the cache');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $items = $this->connection
            ->select(['*'], 'tx_vdsite_queueitem', [], [], [], (int)$input->getOption('number'))
            ->fetchAllAssociative();

        foreach ($items as $item) {
            GeneralUtility::getUrl($item['uri']);

            $this->connection->delete('tx_vdsite_queueitem', ['uid' => $item['uid']]);
        }

        return Command::SUCCESS;
    }
}
