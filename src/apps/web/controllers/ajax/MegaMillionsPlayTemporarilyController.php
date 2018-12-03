<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 5/11/18
 * Time: 14:34
 */

namespace EuroMillions\web\controllers\ajax;


use EuroMillions\shared\vo\RedisOrderKey;
use EuroMillions\web\components\DateTimeUtil;
use EuroMillions\web\vo\LastDrawDate;
use EuroMillions\web\vo\PlayFormToStorage;

class MegaMillionsPlayTemporarilyController extends PowerBallPlayTemporarilyController
{

    public function temporarilyCartAction()
    {
        $powerPlay = $this->request->getPost('power_play');
        $bets = $this->request->getPost('bet');
        $frequency = $this->request->getPost('frequency');
        $startDrawDate = $this->request->getPost('start_draw');
        $draw_day_play = $this->request->getPost('draw_day_play');
        $threshold = $this->request->getPost('threshold');
        $authService = $this->domainServiceFactory->getAuthService();
        $lastDrawDate = new LastDrawDate($startDrawDate,$frequency);
        $date_time_util = new DateTimeUtil();
        $result = null;
        $playFormToStorage_collection = [];

        foreach($bets as $bet) {
            $playFormToStorage = new PlayFormToStorage();
            $playFormToStorage->startDrawDate = $startDrawDate;
            $playFormToStorage->frequency = $frequency;
            $playFormToStorage->powerPlay = $powerPlay;
            $playFormToStorage->lastDrawDate = $lastDrawDate->getLastDrawDate();
            $playFormToStorage->drawDays = $draw_day_play;
            $playFormToStorage->euroMillionsLines = $this->create($bet);
            $playFormToStorage->threshold = $threshold;
            $playFormToStorage->num_weeks = $date_time_util->getNumWeeksBetweenDates(new \DateTime($startDrawDate),new \DateTime($lastDrawDate->getLastDrawDate()));
            $playFormToStorage_collection['play_config'][] = $playFormToStorage->toJson();
        }

        $playService = $this->domainServiceFactory->getPlayService();
        $current_user = $authService->getCurrentUser();
        $this->result = $playService->savePlayFromJson(json_encode($playFormToStorage_collection),RedisOrderKey::create($current_user->getId(),4)->key());
        $this->redirectResult();
    }


    protected function redirectResult($lotteryName = 'powerball')
    {
        parent::redirectResult('megamillions'); // TODO: Change the autogenerated stub
    }


}