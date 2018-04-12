<?php


namespace EuroMillions\web\controllers\ajax;

use EuroMillions\web\components\DateTimeUtil;
use EuroMillions\web\vo\PowerBallLine;
use EuroMillions\web\vo\PowerBallLuckyNumber;
use EuroMillions\web\vo\PowerBallRegularNumber;
use EuroMillions\web\vo\LastDrawDate;
use EuroMillions\web\vo\PlayFormToStorage;

class PowerBallPlayTemporarilyController extends AjaxControllerBase
{


    public function temporarilyCartAction()
    {
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
            $playFormToStorage->lastDrawDate = $lastDrawDate->getLastDrawDate();
            $playFormToStorage->drawDays = $draw_day_play;
            $playFormToStorage->euroMillionsLines = $this->create($bet);
            $playFormToStorage->threshold = $threshold;
            $playFormToStorage->num_weeks = $date_time_util->getNumWeeksBetweenDates(new \DateTime($startDrawDate),new \DateTime($lastDrawDate->getLastDrawDate()));
            $playFormToStorage_collection['play_config'][] = $playFormToStorage->toJson();
        }

        $playService = $this->domainServiceFactory->getPlayService();
        $current_user = $authService->getCurrentUser();
        $result = $playService->savePlayFromJson(json_encode($playFormToStorage_collection),$current_user->getId());
        if($result->success()) {
            echo json_encode(['result'=>'OK', 'url' => '/powerball/cart/profile']);
        } else {
            echo json_encode(['result'=> $result->errorMessage()]);
        }
    }

    /**
     * @param $bets
     * @return array PowerBallLine
     */
    private function create($numbers)
    {

        /**
         * @param $number
         * @return PowerBallRegularNumber
         */
        $callbackN = function($number) {
            return new PowerBallRegularNumber((int) $number);
        };
        /**
         * @param $number
         * @return PowerBallLuckyNumber
         */
        $callbackL = function($number) {
            return new PowerBallLuckyNumber((int) $number);
        };

        $regular_numbers = array_map($callbackN,array_slice(explode(',',$numbers),0,5));
        $lucky_numbers = array_map($callbackL,array_slice(explode(',',$numbers),5,8));
        $euroMillionsLine[] = new PowerBallLine($regular_numbers,$lucky_numbers);

        return $euroMillionsLine;
    }
}