<?php


namespace EuroMillions\controllers\ajax;


use EuroMillions\vo\EuroMillionsLine;
use EuroMillions\vo\EuroMillionsLuckyNumber;
use EuroMillions\vo\EuroMillionsRegularNumber;
use EuroMillions\vo\LastDrawDate;
use EuroMillions\vo\PlayFormToStorage;

class PlayTemporarilyController extends AjaxControllerBase
{


    public function temporarilyCartAction()
    {
        $bets = $this->request->getPost('bet');
        $frequency = $this->request->getPost('frequency');
        $startDrawDate = $this->request->getPost('start_draw');
        $drawDays = $this->request->getPost('draw_days');
        $authService = $this->domainServiceFactory->getAuthService();
        $lastDrawDate = new LastDrawDate($startDrawDate,$frequency);

        $playFormToStorage = new PlayFormToStorage();
        $playFormToStorage->startDrawDate = $startDrawDate;
        $playFormToStorage->frequency = $startDrawDate;
        $playFormToStorage->lastDrawDate = $lastDrawDate->getLastDrawDate();
        $playFormToStorage->drawDays = $drawDays;
        $playFormToStorage->euroMillionsLines = $this->create($bets);

        $playService = $this->domainServiceFactory->getPlayService();
        $current_user = $authService->getCurrentUser();
        $result = $playService->temporarilyStorePlay($playFormToStorage,$current_user->getId());

        if($result->success()) {
            echo json_encode(['result'=>'OK']);
        } else {
            echo json_encode(['result'=> $result->errorMessage()]);
        }

    }

    /**
     * @param $bets
     * @return array EuroMillionsLine
     */
    private function create($bets)
    {
        $euroMillionsLine = [];

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
        foreach($bets as $numbers){
            $regular_numbers = array_map($callbackN,array_slice(explode(',',$numbers),0,5));
            $lucky_numbers = array_map($callbackL,array_slice(explode(',',$numbers),5,8));
            $euroMillionsLine[] = new EuroMillionsLine($regular_numbers,$lucky_numbers);
        }

        return $euroMillionsLine;
    }
}