<?php

namespace EuroMillions\web\controllers;

use EuroMillions\web\components\DateTimeUtil;
use EuroMillions\web\components\tags\MetaDescriptionTag;
use EuroMillions\web\components\tags\PowerBallWidgetTag;
use EuroMillions\web\components\tags\EuroMillionsWidgetTag;
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
        $jackpot = $this->userPreferencesService->getJackpotInMyCurrencyAndMillions($this->lotteryService->getNextJackpot('EuroMillions'));
        $this->view->setVar('jackpot_value', ViewHelper::formatJackpotNoCents($jackpot));
        $numbers = preg_replace('/[A-Z,.]/','',ViewHelper::formatJackpotNoCents($jackpot));
        $letters = preg_replace('/[0-9.,]/','',ViewHelper::formatJackpotNoCents($jackpot));
        $this->view->setVar('milliards', false);
        $this->view->setVar('trillions', false);
        if ($numbers > 1000 && $this->languageService->getLocale() != 'es_ES') {
            $numbers = round(($numbers / 1000), 1);
            $this->view->setVar('jackpot_value', $letters . ' ' . $numbers);
            $this->view->setVar('milliards', true);
        } elseif ($numbers > 1000000 && $this->languageService->getLocale() != 'es_ES') {
            $numbers = round(($numbers / 1000000), 1);
            $this->view->setVar('jackpot_value', $letters . ' ' . $numbers);
            $this->view->setVar('trillions', true);
        } else{
            $this->view->setVar('milliards', false);
            $this->view->setVar('trillions', false);
        }
//        $widgetValues = $this->lotteryService->getNextDrawAndJackpotForAllLotteries();
//        foreach ($widgetValues as $key=>$values) {
//            $widget = 'EuroMillions\web\components\tags\\'.$key.'WidgetTag';
//            $widget = new $widget();
//            $widget::setTime($values['show_days']);
//            $widget::setJackpot($values['jackpot_value']);
//            $widget::setLink($values['link']);
//            $widget::setName($values['name']);
//        }


        $this->view->setVar('language', $this->languageService->getLocale());
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
            'last_result' => ['regular_numbers' => $euroMillionsDraw->getResult()->getRegularNumbersArray(), 'lucky_numbers' => $euroMillionsDraw->getResult()->getLuckyNumbersArray()],
            'date_draw' => $this->lotteryService->getNextDateDrawByLottery('EuroMillions')->modify('-1 hours')->format('Y-m-d H:i:s'),
            'last_draw_date' => $euroMillionsDraw->getDrawDate()->format($this->languageService->translate('dateformat')),
            'draw_day' => $euroMillionsDraw->getDrawDate()->format('l'),
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
        $jackpot = $this->userPreferencesService->getJackpotInMyCurrencyAndMillions($this->lotteryService->getNextJackpot('EuroMillions'));
        $this->view->setVar('jackpot_value', ViewHelper::formatJackpotNoCents($jackpot));
        $numbers = preg_replace('/[A-Z,.]/','',ViewHelper::formatJackpotNoCents($jackpot));
        $letters = preg_replace('/[0-9.,]/','',ViewHelper::formatJackpotNoCents($jackpot));
        $this->view->setVar('milliards', false);
        $this->view->setVar('trillions', false);
        if ($numbers > 1000 && $this->languageService->getLocale() != 'es_ES') {
            $numbers = round(($numbers / 1000), 1);
            $this->view->setVar('jackpot_value', $letters . ' ' . $numbers);
            $this->view->setVar('milliards', true);
        } elseif ($numbers > 1000000 && $this->languageService->getLocale() != 'es_ES') {
            $numbers = round(($numbers / 1000000), 1);
            $this->view->setVar('jackpot_value', $letters . ' ' . $numbers);
            $this->view->setVar('trillions', true);
        } else{
            $this->view->setVar('milliards', false);
            $this->view->setVar('trillions', false);
        }
        $this->view->setVar('language', $this->languageService->getLocale());
        $this->tag->prependTitle($this->languageService->translate('resultshist_em_name'));
        MetaDescriptionTag::setDescription($this->languageService->translate('resultshist_em_desc'));

        $this->view->pick('/numbers/past');
        return $this->view->setVars([
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
        $jackpot = $this->userPreferencesService->getJackpotInMyCurrencyAndMillions($this->lotteryService->getNextJackpot('EuroMillions'));
        $this->view->setVar('jackpot_value', ViewHelper::formatJackpotNoCents($jackpot));
        $numbers = preg_replace('/[A-Z,.]/', '', ViewHelper::formatJackpotNoCents($jackpot));
        $letters = preg_replace('/[0-9.,]/', '', ViewHelper::formatJackpotNoCents($jackpot));
        $this->view->setVar('milliards', false);
        $this->view->setVar('trillions', false);
        if ($numbers > 1000 && $this->languageService->getLocale() != 'es_ES') {
            $numbers = round(($numbers / 1000), 1);
            $this->view->setVar('jackpot_value', $letters . ' ' . $numbers);
            $this->view->setVar('milliards', true);
        } elseif ($numbers > 1000000 && $this->languageService->getLocale() != 'es_ES') {
            $numbers = round(($numbers / 1000000), 1);
            $this->view->setVar('jackpot_value', $letters . ' ' . $numbers);
            $this->view->setVar('trillions', true);
        } else {
            $this->view->setVar('milliards', false);
            $this->view->setVar('trillions', false);
        }
        $this->view->setVar('language', $this->languageService->getLocale());
        $date = $params[0];
        $lotteryName = 'EuroMillions';
        $actualDate = new \DateTime();
        $date = empty($date) ? new \DateTime() : new \DateTime($date);
        $draw_result = $this->lotteryService->getDrawWithBreakDownByDate($lotteryName, $date);
        $draw = $this->lotteryService->getNextDateDrawByLottery('Euromillions');
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
            'last_draw_date' => $euroMillionsDraw->getDrawDate()->format('D, d M Y'),
            'date_canonical' => $euroMillionsDraw->getDrawDate()->format('Y-m-d'),
            'date_draw' => $this->lotteryService->getNextDateDrawByLottery('EuroMillions')->modify('-1 hours')->format('Y-m-d H:i:s'),
            'symbol' => $this->userPreferencesService->getMyCurrencyNameAndSymbol()['symbol'],
            'show_s_days' => (new \DateTime())->diff($this->lotteryService->getNextDateDrawByLottery('EuroMillions')->modify('-1 hours'))->format('%a'),
            'actual_year' => $actualDate->format('Y'),
            'pageController' => 'euroPastResult',
            'draw_day' => $euroMillionsDraw->getDrawDate()->format('l'),
            'next_draw_date_format' => $draw->format($this->languageService->translate('dateformat')),
            'past_draw_date_format' => $euroMillionsDraw->getDrawDate()->format('d.m.Y'),
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
