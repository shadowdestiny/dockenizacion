<?php


namespace EuroMillions\web\repositories;


use EuroMillions\web\entities\Bet;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\entities\LogValidationApi;
use EuroMillions\web\entities\PlayConfig;

class LogValidationApiRepository extends RepositoryBase
{

    public function persistValidationsAndBetsFromPlayConfigsCollection(array $playConfigs,
                                                                       EuroMillionsDraw $draw,
                                                                       $id_ticket,
                                                                       $status = 'OK')
    {

        if( count($playConfigs) == 0 ) {
            throw new \Exception('PlayConfigs collection is empty');
        }

        /** @var PlayConfig $playConfig */
        foreach($playConfigs as $playConfig) {
            $bet = new Bet($playConfig,$draw);
            $log_api_reponse = new LogValidationApi();
            $log_api_reponse->initialize([
                'id_provider' => 1,
                'id_ticket' => $id_ticket,
                'status' => $status,
                'response' => '',
                'received' => new \DateTime(),
                'bet' => $bet
            ]);
            $this->add($bet);
            $this->getEntityManager()->flush($bet);
            $this->add($log_api_reponse);
            $this->add($playConfig);
            $this->getEntityManager()->flush($log_api_reponse);
        }
    }
}