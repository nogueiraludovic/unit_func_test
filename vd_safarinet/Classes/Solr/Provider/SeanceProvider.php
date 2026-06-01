<?php

declare(strict_types=1);

namespace Vd\VdSafarinet\Solr\Provider;

use DateTime;
use TYPO3\CMS\Core\Routing\RouterInterface;
use Vd\VdSolr\Domain\Model\ExternalDocument;

use function html_entity_decode;
use function strip_tags;

use const ENT_QUOTES;

final class SeanceProvider extends AbstractSourceProvider
{
    public function getDocuments(array $options): array
    {
        if ((bool)$options['force'] === true) {
            $gcMeetings = $this->sielService->fetchGreatCouncilMeetings();
        } else {
            $gcMeetings = $this->sielService->fetchGreatCouncilMeetings([
                'dateDebut' => (new DateTime())->modify('-60 days')->format('Y-m-d'),
                'dateFin' => (new DateTime())->modify('+1 day')->format('Y-m-d')
            ]);
        }

        if (isset($gcMeetings['id']) === true) {
            $tempGcMeetings = $gcMeetings;

            unset($gcMeetings);

            $gcMeetings[] = $tempGcMeetings;
        }

        $uid = 1;

        foreach ($gcMeetings as $gcMeeting) {
            $gcMeetingItem = $this->sielService->fetchGreatCouncilMeeting((string)$gcMeeting['id']);

            if (isset($gcMeetingItem['id']) === false || isset($this->documents[$gcMeetingItem['id']]) === true) {
                continue;
            }

            $gcMeetingItem['uid'] = $uid;

            $this->documents[$gcMeetingItem['id']] = $this->createSeance($gcMeetingItem);

            ++$uid;

            foreach ($gcMeetingItem['points']['item'] ?? [] as $gcPoint) {
                $gcPointItem = $this->sielService->fetchGreatCouncilPoint((string)$gcPoint['id']);

                if (isset($gcPointItem['id']) === false || isset($this->documents[$gcPointItem['id']]) === true) {
                    continue;
                }

                $gcPointItem['seance']['dateSeance'] = DateTime::createFromFormat(
                    'Y-m-d',
                    $gcMeetingItem['dateSeance']
                );
                $gcPointItem['uid'] = $uid;

                $this->documents[$gcPointItem['id']] = $this->createPoint($gcPointItem);

                ++$uid;
            }
        }

        return $this->documents;
    }

    protected function createPoint(array $point): ExternalDocument
    {
        $externalDocument = (new ExternalDocument())
            ->setChanged(new DateTime())
            ->setCreated($point['seance']['dateSeance'])
            ->setSite($this->domain)
            ->setSiteHash()
            ->setTitle($point['intitule'])
            ->setType('siel_gc')
            ->setUid($point['uid'])
            ->setUrl(
                (string)$this->site->getRouter()->generateUri(
                    2017368,
                    [
                        'tx_vdsafarinet_safarinet' => [
                            'action' => 'gcPointShow',
                            'controller' => 'Meeting',
                            'meetingGcId' => $point['seance']['id'],
                            'pointId' => $point['id']
                        ]
                    ],
                    '',
                    RouterInterface::ABSOLUTE_PATH
                )
            );

        $additionalFields['datetime_dateS'] = $point['seance']['dateSeance']->format('Y-m-d\TH:i:s\Z');

        if (isset($point['texte']) === true) {
            $content = html_entity_decode($point['texte'], ENT_QUOTES);
            $additionalFields['content_textS'] = strip_tags($content);

            $externalDocument->setContent($content);
        }

        $additionalFields['sielId_stringS'] = $point['id'];
        $additionalFields['subType_stringS'] = 'point';

        $externalDocument->setAdditionalFields($additionalFields);

        return $externalDocument;
    }

    protected function createSeance(array $seance): ExternalDocument
    {
        $dateSeance = DateTime::createFromFormat('Y-m-d', $seance['dateSeance']);

        $externalDocument = (new ExternalDocument())
            ->setChanged(DateTime::createFromFormat('Y-m-d', $seance['dateModification']))
            ->setCreated($dateSeance)
            ->setSite($this->domain)
            ->setSiteHash()
            ->setTitle($seance['nomCourt'])
            ->setType('siel_gc')
            ->setUid((int)$seance['uid'])
            ->setUrl(
                (string)$this->site->getRouter()->generateUri(
                    2017369,
                    [
                        'tx_vdsafarinet_safarinet' => [
                            'action' => 'gcMeetingShow',
                            'controller' => 'Meeting',
                            'meetingId' => $seance['id']
                        ]
                    ],
                    '',
                    RouterInterface::ABSOLUTE_PATH
                )
            );

        $additionalFields['datetime_dateS'] = $dateSeance->format('Y-m-d\TH:i:s\Z');

        if (isset($seance['introduction']) === true) {
            $content = html_entity_decode($seance['introduction'], ENT_QUOTES);
            $additionalFields['content_textS'] = strip_tags($content);

            $externalDocument->setContent($content);
        }

        $additionalFields['sielId_stringS'] = $seance['id'];
        $additionalFields['subType_stringS'] = 'seance';

        $externalDocument->setAdditionalFields($additionalFields);

        return $externalDocument;
    }
}
