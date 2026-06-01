<?php

declare(strict_types=1);

namespace Vd\VdSite\Command\Sitemap;

use SimpleXMLElement;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

use function count;
use function getenv;
use function strpos;

class InitSitemapCommand extends AbstractSitemapCommand
{
    protected function addCustomUrl(array $urls): void
    {
        foreach ($urls as $url) {
            if (GeneralUtility::isValidUrl($url) === false) {
                continue;
            }

            $this->addItem($url);
        }
    }

    protected function addItem(string $url): void
    {
        $this->connection->insert('tx_vdsite_queueitem', ['uri' => $url]);
    }

    protected function addSitemapUrl(): void
    {
        $sitemapContent = GeneralUtility::getUrl(getenv('DGNSI_PUBLIC_URL') . 'sitemap?type=1533906435');

        if ($sitemapContent === false) {
            return;
        }

        $xml = new SimpleXMLElement($sitemapContent);

        foreach ($xml->sitemap as $sitemap) {
            $this->addItem((string)$sitemap->loc);
        }
    }

    protected function configure(): void
    {
        $this
            ->addArgument(
                'url',
                InputArgument::IS_ARRAY,
                'Custom URLs to add to the list (separate multiple names with a space)',
                []
            )
            ->addOption('reset', null, InputOption::VALUE_REQUIRED, 'Truncate the queue before initialization', true)
            ->setDescription('Initialize the queue for sitemap');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ((bool)$input->getOption('reset') === true) {
            $this->connection->truncate('tx_vdsite_queueitem');
        }

        $urls = $input->getArgument('url');

        if (count($urls) === 0) {
            $this->addSitemapUrl();
        } else {
            if (count($urls) === 1 && strpos($urls[0], ' ') !== false) {
                $urls = GeneralUtility::trimExplode(' ', $urls[0], true);
            }

            $this->addCustomUrl($urls);
        }

        return Command::SUCCESS;
    }
}
