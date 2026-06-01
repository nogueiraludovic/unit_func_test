<?php

declare(strict_types=1);

namespace Vd\VdContactService\Slot;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Vd\VdContactService\Domain\Model\ServiceContact;
use Vd\VdContactService\Domain\Repository\ServiceContactRepository;

class PowermailSlot
{
    protected ?ServiceContact $serviceContact = null;
    protected ?ServiceContactRepository $serviceContactRepository = null;

    public function __construct(ServiceContactRepository $serviceContactRepository)
    {
        $queryParams = $this->getRequest()->getQueryParams();

        $this->serviceContactRepository = $serviceContactRepository;
        $this->serviceContact = $this->getServiceContact((int)$queryParams['tx_powermail_pi1']['uid']);
    }

    public function manipulateMail(MailMessage $message, array &$email): void
    {
        if ($this->serviceContact === null) {
            return;
        }

        $emailAddress = $this->serviceContact->getEmailAddress();

        if (GeneralUtility::validEmail($emailAddress) === false) {
            return;
        }

        if ($email['template'] === 'Mail/ReceiverMail') {
            $email['receiverEmail'] = $emailAddress;

            $name = $this->serviceContact->getName();

            if ($name !== '') {
                $email['receiverName'] = $this->serviceContact->getName();
            }

            $message->setTo([
                $email['receiverEmail'] => $email['receiverName']
            ]);
        }
    }

    public function prefillFields($field, $mail, $default, $prefillFieldViewHelper): array
    {
        if ($this->serviceContact === null) {
            return [$field, $mail, $default, $prefillFieldViewHelper];
        }

        if ($field->getMarker() === 'contactservicename' || $field->getMarker() === 'servicecontactname') {
            $prefillFieldViewHelper->setValue($this->serviceContact->getService()->getName());
        }

        return [
            $field,
            $mail,
            $default,
            $prefillFieldViewHelper
        ];
    }

    protected function getRequest(): ServerRequestInterface
    {
        return $GLOBALS['TYPO3_REQUEST'];
    }

    protected function getServiceContact(int $uid): ?ServiceContact
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->serviceContactRepository->findByUid($uid);
    }
}
