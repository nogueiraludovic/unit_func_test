<?php

declare(strict_types=1);

namespace Vd\VdSite\Updates;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Install\Updates\DatabaseUpdatedPrerequisite;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;
use Vd\VdPowermail\Domain\Repository\FormRepository;

use function count;
use function time;

class AddTurnstileCaptchaUpdate implements UpgradeWizardInterface
{
    protected const TURNSTILE_FIELD_TYPE = 'turnstileCaptcha';

    protected ConnectionPool $connection;
    protected FormRepository $formRepository;

    public function __construct(ConnectionPool $connection, FormRepository $formRepository)
    {
        $this->connection = $connection;
        $this->formRepository = $formRepository;
    }

    public function executeUpdate(): bool
    {
        $forms = $this->formRepository->findAll();

        foreach ($forms as $form) {
            $this->checkCaptchaInForm($form);
        }

        return true;
    }

    public function getDescription(): string
    {
        return 'Add turnstile captcha to each powermail form.';
    }

    public function getIdentifier(): string
    {
        return 'vdSiteAddTurnstileCaptcha';
    }

    public function getPrerequisites(): array
    {
        return [
            DatabaseUpdatedPrerequisite::class
        ];
    }

    public function getTitle(): string
    {
        return 'vd_site: Add turnstile captcha';
    }

    public function updateNecessary(): bool
    {
        return true;
    }

    protected function checkCaptchaInForm($form): void
    {
        $pages = $form->getPages();

        if (count($pages) === 0) {
            return;
        }

        $queryBuilder = $this->connection->getQueryBuilderForTable('tx_powermail_domain_model_field');
        $queryBuilder->getRestrictions()->removeAll();

        $lastField = null;
        $lastSubmitField = null;

        foreach ($pages as $page) {
            $fields = $queryBuilder
                ->select('*')
                ->from('tx_powermail_domain_model_field')
                ->where($queryBuilder->expr()->eq('page', $page->getUid()))
                ->orderBy('sorting')
                ->execute()
                ->fetchAllAssociative();

            foreach ($fields as $field) {
                $lastField = $field;

                if ($field['type'] === 'submit') {
                    $lastSubmitField = $field;
                }

                if ($field['type'] !== 'captcha' && $field['type'] !== self::TURNSTILE_FIELD_TYPE) {
                    continue;
                }

                $queryBuilder
                    ->update('tx_powermail_domain_model_field')
                    ->set('hidden', 0)
                    ->set('type', self::TURNSTILE_FIELD_TYPE)
                    ->where(
                        $queryBuilder->expr()->eq(
                            'uid',
                            $queryBuilder->createNamedParameter($field['uid'], Connection::PARAM_INT)
                        )
                    )
                    ->execute();

                return;
            }
        }

        if ($lastSubmitField !== null) {
            $lastField = $lastSubmitField;
        }

        $lastPage = $pages[count($pages) - 1];

        $pageUid = $lastPage->getUid();
        $pid = $lastPage->getPid();
        $sorting = 0;

        if ($lastField !== null) {
            $pageUid = $lastField['page'];
            $pid = $lastField['pid'];
            $sorting = $lastField['sorting'];

            $queryBuilder
                ->update('tx_powermail_domain_model_field')
                ->set('sorting', $sorting + 1)
                ->where(
                    $queryBuilder->expr()->eq(
                        'uid',
                        $queryBuilder->createNamedParameter($lastField['uid'], Connection::PARAM_INT)
                    )
                )
                ->execute();
        }

        $queryBuilder
            ->insert('tx_powermail_domain_model_field')
            ->values([
                'crdate' => time(),
                'marker' => 'captcha',
                'page' => $pageUid,
                'pid' => $pid,
                'sorting' => $sorting,
                'title' => 'Captcha',
                'tstamp' => time(),
                'type' => self::TURNSTILE_FIELD_TYPE
            ])
            ->execute();
    }
}
