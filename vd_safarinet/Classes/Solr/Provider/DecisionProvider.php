<?php

declare(strict_types=1);

namespace Vd\VdSafarinet\Solr\Provider;

use DateTime;
use TYPO3\CMS\Core\Routing\RouterInterface;
use Vd\VdSolr\Domain\Model\ExternalDocument;

use function html_entity_decode;
use function strip_tags;

use const ENT_QUOTES;

final class DecisionProvider extends AbstractSourceProvider
{
    public function getDocuments(array $options): array
    {
        if ((bool)$options['force'] === true) {
            $meetings = $this->sielService->fetchMeetings();
        } else {
            $meetings = $this->sielService->fetchMeetings([
                'dateDebut' => (new DateTime())->modify('-60 days')->format('Y-m-d'),
                'dateFin' => (new DateTime())->modify('+1 day')->format('Y-m-d')
            ]);
        }

        if (isset($meetings['id']) === true) {
            $tempMeetings = $meetings;

            unset($meetings);

            $meetings[] = $tempMeetings;
        }

        $uid = 1;

        foreach ($meetings as $meeting) {
            $meetingItem = $this->sielService->fetchMeeting((string)$meeting['id']);

            if (isset($meetingItem['id']) === false) {
                continue;
            }

            foreach ($meetingItem['decisions']['decision'] ?? [] as $decision) {
                if (isset($decision['id']) === false || isset($this->documents[$decision['id']]) === true) {
                    continue;
                }

                $decision['seance']['dateSeance'] = DateTime::createFromFormat('Y-m-d', $meetingItem['dateSeance']);
                $decision['uid'] = $uid;

                $this->documents[$decision['id']] = $this->createDecision($decision);

                ++$uid;
            }
        }

        return $this->documents;
    }

    protected function createDecision(array $decision): ExternalDocument
    {
        $externalDocument = (new ExternalDocument())
            ->setChanged(new DateTime())
            ->setCreated($decision['seance']['dateSeance'])
            ->setSite($this->domain)
            ->setSiteHash()
            ->setType('siel_ce')
            ->setUid($decision['uid'])
            ->setUrl(
                (string)$this->site->getRouter()->generateUri(
                    2003662,
                    [
                        'tx_vdsafarinet_safarinet' => [
                            'action' => 'show',
                            'controller' => 'Decision',
                            'decisionId' => $decision['id']
                        ]
                    ],
                    '',
                    RouterInterface::ABSOLUTE_PATH
                )
            );

        $additionalFields['datetime_dateS'] = $decision['seance']['dateSeance']->format('Y-m-d\TH:i:s\Z');

        if (isset($decision['resume']) === true) {
            $content = html_entity_decode($decision['resume'], ENT_QUOTES);
            $additionalFields['content_textS'] = strip_tags($content);

            $externalDocument->setContent($content);
        }

        $additionalFields['sielId_stringS'] = $decision['id'];
        $additionalFields['subType_stringS'] = 'decision';

        $externalDocument->setAdditionalFields($additionalFields);

        if (isset($decision['titre']) === true) {
            $externalDocument->setTitle($decision['titre']);
        }

        return $externalDocument;
    }
}
