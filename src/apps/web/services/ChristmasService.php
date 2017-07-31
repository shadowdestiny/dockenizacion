<?php

namespace EuroMillions\web\services;

use Doctrine\ORM\EntityManager;
use EuroMillions\web\components\CypherCastillo3DES;
use EuroMillions\web\components\CypherCastillo3DESLive;
use EuroMillions\web\repositories\ChristmasTicketsRepository;

class ChristmasService
{
    protected $entityManager;
    /** @var ChristmasTicketsRepository  */
    protected $christmasTicketsRepository;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->christmasTicketsRepository = $entityManager->getRepository('EuroMillions\web\entities\ChristmasTickets');
    }

    public function getAvailableTickets()
    {
        return $this->christmasTicketsRepository->getAvailableTickets();
    }

    /**
     * @param $chistmasPost
     * @return array
     */
    public function getChristmasTicketsData($chistmasPost)
    {
        $christmasTicketsData = [];
        foreach ($chistmasPost as $key => $value) {
            $id = explode('_', $key)[1];
            if ($value != 0) {
                for ($i=0;$i<$value;$i++) {
                    $christmasTicketsData[] = $this->christmasTicketsRepository->findOneBy(['id' => $id]);
                }
            }
        }

        return $christmasTicketsData;
    }

    public function insertStockXML($xmlPost)
    {
//        $di = \Phalcon\Di::getDefault();
//        $cypher = $di->get('environmentDetector')->get() != 'production' ? new CypherCastillo3DES() : new CypherCastillo3DESLive();
        $cypher = new CypherCastillo3DESLive();
        foreach ($xmlPost as $xml) {
            $xml_response = simplexml_load_string($xml);
            $xml_uncyphered_string = $cypher->decrypt((string)$xml_response->operation->content, intval($xml_response->operation['key']));
            $xml_uncyphered = simplexml_load_string($xml_uncyphered_string);
            $ticketsChristmas = explode(',*', preg_replace('[\n|\r|\n\r|\t|\0|\x0B]', '', $xml_uncyphered->csv));
            foreach ($ticketsChristmas as $ticket) {
                if ($ticket != '') {
                    $ticketArray = explode(',', $ticket);
                    $ticketExist = $this->christmasTicketsRepository->findOneBy(['number' => $ticketArray[0], 'serieInit' => $ticketArray[1]]);
                    if (is_null($ticketExist)) {
                        $this->christmasTicketsRepository->insertTicket($ticketArray);
                    }
                }
            }
        }
    }
}
