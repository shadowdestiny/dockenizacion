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
        $draw_result = $this->lotteryService->getLastDrawWithBreakDownByDate($lotteryName, $date);
        if (!$result->success()) {
            return $this->view->setVars([
                'error' => $result->errorMessage()
            ]);
        }
        /** @var EuroMillionsDraw $euroMillionsDraw */
        $euroMillionsDraw = $draw_result->getValues();
        $breakDownDTO = new EuroMillionsDrawBreakDownDTO($euroMillionsDraw->getBreakDown());
        $break_down_list = $this->convertCurrency($breakDownDTO->toArray());

        $this->tag->prependTitle($this->languageService->translate('results_em_name'));
        MetaDescriptionTag::setDescription($this->languageService->translate('results_em_desc'));
        return $this->view->setVars([
            'break_downs' => !empty($break_down_list) ? $break_down_list : '',
            'id_draw' => $euroMillionsDraw->getId(),
            'jackpot_value' => ViewHelper::formatJackpotNoCents($this->userPreferencesService->getJackpotInMyCurrencyAndMillions($this->lotteryService->getNextJackpot('EuroMillions'))),
            'last_result' => ['regular_numbers' => $euroMillionsDraw->getResult()->getRegularNumbersArray(), 'lucky_numbers' => $euroMillionsDraw->getResult()->getLuckyNumbersArray()],
            'date_draw' => $this->lotteryService->getNextDateDrawByLottery('EuroMillions')->modify('-1 hours')->format('Y-m-d H:i:s'),
            'last_draw_date' => $euroMillionsDraw->getDrawDate()->format('D, d M Y'),
            'symbol' => $this->userPreferencesService->getMyCurrencyNameAndSymbol()['symbol'],
            'list_draws' => $result->getValues(),
            'show_s_days' => (new \DateTime())->diff($this->lotteryService->getNextDateDrawByLottery('EuroMillions')->modify('-1 hours'))->format('%a'),
            'actual_year' => (new \DateTime())->format('Y'),
            'pageController' => 'euroResult',
        ]);
    }

    public function pastListAction()
    {
        $lotteryName = 'EuroMillions';
        $result = $this->lotteryService->getDrawsDTO($lotteryName, 1000);
        if (!$result->success()) {
            return $this->view->setVars([
                'error' => $result->errorMessage()
            ]);
        }

        $this->tag->prependTitle($this->languageService->translate('resultshist_em_name'));
        MetaDescriptionTag::setDescription($this->languageService->translate('resultshist_em_desc'));

        $this->view->pick('/numbers/past');
        return $this->view->setVars([
            'jackpot_value' => ViewHelper::formatJackpotNoCents($this->userPreferencesService->getJackpotInMyCurrencyAndMillions($this->lotteryService->getNextJackpot('EuroMillions'))),
            'date_draw' => $this->lotteryService->getNextDateDrawByLottery('EuroMillions')->modify('-1 hours')->format('Y-m-d H:i:s'),
            'show_s_days' => (new \DateTime())->diff($this->lotteryService->getNextDateDrawByLottery('EuroMillions')->modify('-1 hours'))->format('%a'),
            'symbol' => $this->userPreferencesService->getMyCurrencyNameAndSymbol()['symbol'],
            'list_draws' => $result->getValues(),
            'pageController' => 'euroPastResult',
        ]);
    }

    public function pastResultAction()
    {
        $params = $this->router->getParams();
        if (!isset($params[0])) {
            $this->response->redirect($this->lottery . 'results');
        }
        $date = $params[0];
        $lotteryName = 'EuroMillions';
        $actualDate = new \DateTime();
        $date = empty($date) ? new \DateTime() : new \DateTime($date);
        $draw_result = $this->lotteryService->getDrawWithBreakDownByDate($lotteryName, $date);
        /** @var EuroMillionsDraw $euroMillionsDraw */
        $euroMillionsDraw = $draw_result->getValues();
        $breakDownDTO = new EuroMillionsDrawBreakDownDTO($euroMillionsDraw->getBreakDown());
        $break_down_list = $this->convertCurrency($breakDownDTO->toArray());

        $this->tag->prependTitle($this->languageService->translate('resultsdate_em_name') . $this->languageService->translate($date->format('l')) . ', ' . $date->format($this->languageService->translate('dateformat')));
        MetaDescriptionTag::setDescription($this->languageService->translate('resultsdate_em_desc'));

        $this->view->pick('/numbers/past-draw');
        return $this->view->setVars([
            'break_downs' => !empty($break_down_list) ? $break_down_list : '',
            'id_draw' => $euroMillionsDraw->getId(),
            'last_result' => ['regular_numbers' => $euroMillionsDraw->getResult()->getRegularNumbersArray(), 'lucky_numbers' => $euroMillionsDraw->getResult()->getLuckyNumbersArray()],
            'jackpot_value' => ViewHelper::formatJackpotNoCents($this->userPreferencesService->getJackpotInMyCurrencyAndMillions($this->lotteryService->getNextJackpot('EuroMillions'))),
            'last_draw_date' => $euroMillionsDraw->getDrawDate()->format('D, d M Y'),
            'date_canonical' => $euroMillionsDraw->getDrawDate()->format('Y-m-d'),
            'date_draw' => $this->lotteryService->getNextDateDrawByLottery('EuroMillions')->modify('-1 hours')->format('Y-m-d H:i:s'),
            'symbol' => $this->userPreferencesService->getMyCurrencyNameAndSymbol()['symbol'],
            'show_s_days' => (new \DateTime())->diff($this->lotteryService->getNextDateDrawByLottery('EuroMillions')->modify('-1 hours'))->format('%a'),
            'actual_year' => $actualDate->format('Y'),
            'pageController' => 'euroPastResult',
        ]);
    }

    private function convertCurrency(array $break_downs)
    {
        $user_currency = $this->userPreferencesService->getCurrency();
        if (!empty($break_downs)) {
            foreach ($break_downs as &$breakDown) {
                $breakDown['lottery_prize'] = $this->currencyConversionService->convert(new Money((int)$breakDown['lottery_prize'], new Currency('EUR')), $user_currency)->getAmount() / 10000;
            }
        }
        return $break_downs;
    }

}
