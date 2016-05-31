<?php


namespace EuroMillions\web\controllers;

use EuroMillions\web\components\tags\MetaDescriptionTag;
use EuroMillions\web\components\ViewHelper;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\vo\dto\EuroMillionsDrawBreakDownDTO;
use Money\Currency;
use Money\Money;


class NumbersController extends PublicSiteControllerBase
{

    public function indexAction()
    {
        $date = $this->request->get('date');
        $lotteryName = 'EuroMillions';
        $date = empty($date) ? $this->lotteryService->getLastDrawDate('EuroMillions') : new \DateTime($date);
        $result = $this->lotteryService->getDrawsDTO($lotteryName);
        $draw_result = $this->lotteryService->getLastDrawWithBreakDownByDate($lotteryName,$date);
        if(!$result->success()) {
            return $this->view->setVars([
               'error' => $result->errorMessage()
            ]);
        }
        /** @var EuroMillionsDraw $euroMillionsDraw */
        $euroMillionsDraw = $draw_result->getValues();
        $breakDownDTO = new EuroMillionsDrawBreakDownDTO($euroMillionsDraw->getBreakDown());
        $break_down_list = $this->convertCurrency($breakDownDTO->toArray());

        $this->tag->prependTitle('Euromillions Results & Winning Numbers');
	MetaDescriptionTag::setDescription('Check the EuroMillions results and prize breakdown. Follow each EuroMillions draw and find out if you are the fortunate winner of a big jackpot prize!'); 

        return $this->view->setVars([
            'break_downs' => !empty($break_down_list) ? $break_down_list : '',
            'id_draw' => $euroMillionsDraw->getId(),
            'jackpot_value' => ViewHelper::formatJackpotNoCents($this->userPreferencesService->getJackpotInMyCurrency($this->lotteryService->getNextJackpot('EuroMillions'))),
            'last_result' => ['regular_numbers' => $euroMillionsDraw->getResult()->getRegularNumbersArray(), 'lucky_numbers' => $euroMillionsDraw->getResult()->getLuckyNumbersArray() ],
            'date_draw' =>  $this->lotteryService->getNextDateDrawByLottery('EuroMillions')->format('Y-m-d H:i:s'),
            'last_draw_date' => $euroMillionsDraw->getDrawDate()->format('D, d M Y'),
            'symbol' => $this->userPreferencesService->getMyCurrencyNameAndSymbol()['symbol'],
            'list_draws' => $result->getValues(),
        ]);
    }

    public function pastListAction()
    {
        $lotteryName = 'EuroMillions';
        $result = $this->lotteryService->getDrawsDTO($lotteryName, 1000);
        if(!$result->success()) {
            return $this->view->setVars([
                'error' => $result->errorMessage()
            ]);
        }

	$this->tag->prependTitle('EuroMillions Draw History');
	MetaDescriptionTag::setDescription('Find the results and prize breakdown of any draw since in EuroMillions Lottery History!');

        $this->view->pick('/numbers/past');
        return $this->view->setVars([
            'jackpot_value' =>$this->userPreferencesService->getJackpotInMyCurrency($this->lotteryService->getNextJackpot('EuroMillions')),
            'date_draw' =>  $this->lotteryService->getNextDateDrawByLottery('EuroMillions')->format('Y-m-d H:i:s'),
            'symbol' => $this->userPreferencesService->getMyCurrencyNameAndSymbol()['symbol'],
            'list_draws' => $result->getValues(),
        ]);
    }

    public function pastResultAction()
    {
        $params = $this->router->getParams();
        if(!isset($params[0])) {
            $this->response->redirect($this->lottery.'results');
        }
        $date = $params[0];
        $lotteryName = 'EuroMillions';
        $date = empty($date) ? new \DateTime() : new \DateTime($date);
        $draw_result = $this->lotteryService->getDrawWithBreakDownByDate($lotteryName,$date);
        /** @var EuroMillionsDraw $euroMillionsDraw */
        $euroMillionsDraw = $draw_result->getValues();
        $breakDownDTO = new EuroMillionsDrawBreakDownDTO($euroMillionsDraw->getBreakDown());
        $break_down_list = $this->convertCurrency($breakDownDTO->toArray());

	$this->tag->prependTitle('EuroMillions Results of ' . $date->format('l, d/m/Y'));
	MetaDescriptionTag::setDescription('Check the latest EuroMillions results and prize breakdown and find out if you are the fortunate winner of a big lottery jackpot prize!');

        $this->view->pick('/numbers/past-draw');
        return $this->view->setVars([
            'break_downs' => !empty($break_down_list) ? $break_down_list : '',
            'id_draw' => $euroMillionsDraw->getId(),
            'last_result' => ['regular_numbers' => $euroMillionsDraw->getResult()->getRegularNumbersArray(), 'lucky_numbers' => $euroMillionsDraw->getResult()->getLuckyNumbersArray() ],
            'jackpot_value' =>$this->userPreferencesService->getJackpotInMyCurrency($this->lotteryService->getNextJackpot('EuroMillions')),
            'last_draw_date' => $euroMillionsDraw->getDrawDate()->format('D, d M Y'),
            'date_draw' =>  $this->lotteryService->getNextDateDrawByLottery('EuroMillions')->format('Y-m-d H:i:s'),
            'symbol' => $this->userPreferencesService->getMyCurrencyNameAndSymbol()['symbol'],
        ]);
    }

    private function convertCurrency(array $break_downs)
    {
        $user_currency = $this->userPreferencesService->getCurrency();
        if(!empty($break_downs)) {
            foreach($break_downs as &$breakDown) {
                $breakDown['lottery_prize'] = $this->currencyConversionService->convert(new Money((int) $breakDown['lottery_prize'], new Currency('EUR')), $user_currency)->getAmount() / 10000;
            }
        }
        return $break_downs;
    }

}
