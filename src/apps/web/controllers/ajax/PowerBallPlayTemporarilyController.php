<?php


namespace EuroMillions\web\controllers\ajax;

use EuroMillions\shared\vo\RedisOrderKey;
use EuroMillions\web\components\DateTimeUtil;
use EuroMillions\web\vo\EuroMillionsLine;
use EuroMillions\web\vo\EuroMillionsLuckyNumber;
use EuroMillions\web\vo\EuroMillionsRegularNumber;
use EuroMillions\web\vo\LastDrawDate;
use EuroMillions\web\vo\LastDrawDatePowerBall;
use EuroMillions\web\vo\PlayFormToStorage;

class PowerBallPlayTemporarilyController extends AjaxControllerBase
{

    protected $result;

    public function temporarilyCartAction()
    {
        $powerPlay = $this->request->getPost('power_play');
        $bets = $this->request->getPost('bet');
        $frequency = $this->request->getPost('frequency');
        $startDrawDate = $this->request->getPost('start_draw');
        $draw_day_play = $this->request->getPost('draw_day_play');
        $threshold = $this->request->getPost('threshold');
        $authService = $this->domainServiceFactory->getAuthService();
        $lastDrawDate = new LastDrawDatePowerBall($startDrawDate,$frequency);
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
        $this->result = $playService->savePlayFromJson(json_encode($playFormToStorage_collection),RedisOrderKey::create($current_user->getId(),3)->key());
        $this->redirectResult();
    }

    protected function redirectResult($lotteryName='powerball')
    {
        if($this->result->success()) {
            echo json_encode(['result'=>'OK', 'url' => '/'.$lotteryName.'/cart/profile']);
        } else {
            echo json_encode(['result'=> $this->result->errorMessage()]);
        }
    }


    /**
     * @param $bets
     * @return array EuroMillionsLine
     */
    protected function create($numbers)
    {

        /**
         * @param $number
         * @return EuroMillionsRegularNumber
         */
        $callbackN = function($number) {
            return new EuroMillionsRegularNumber((int) $number);
        };
        /**
         * @param $number
         * @return EuroMillionsLuckyNumber
         */
        $callbackL = function($number) {
            return new EuroMillionsLuckyNumber((int) $number);
        };

        $regular_numbers = array_map($callbackN,array_slice(explode(',',$numbers),0,5));
        $lucky_numbers = array_map($callbackL,array_slice(explode(',',$numbers),5,8));
        $euroMillionsLine[] = new EuroMillionsLine($regular_numbers,$lucky_numbers);

        return $euroMillionsLine;
    }
}