<?php
/**
 * Created by PhpStorm.
 * User: vapdl
 * Date: 11/10/18
 * Time: 02:31 PM
 */

namespace EuroMillions\superenalotto\controllers;

use EuroMillions\shared\components\widgets\JackpotAndCountDownWidget;
use EuroMillions\web\components\DateTimeUtil;
use EuroMillions\web\components\EmTranslationAdapter;
use EuroMillions\web\components\tags\MetaDescriptionTag;
use EuroMillions\web\components\ViewHelper;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\repositories\TranslationDetailRepository;
use EuroMillions\web\services\preferences_strategies\WebLanguageStrategy;
use EuroMillions\superenalotto\vo\dto\SuperEnalottoDrawBreakDownDTO;
use EuroMillions\shared\controllers\PublicSiteControllerBase;
use Money\Currency;
use Money\Money;

final class SuperEnalottoNumbersController extends PublicSiteControllerBase
{

    public function indexAction()
    {
        $jackpot = $this->userPreferencesService->getJackpotInMyCurrencyAndMillions($this->lotteryService->getNextJackpot('SuperEnalotto'));
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
        $lotteryName = 'SuperEnalotto';
        $date = new \DateTime();
        $actualDate = $date;
        $draw_result = $this->lotteryService->getLastDrawDataWithBreakDownByDate($lotteryName, $date);

        if (!$draw_result->getValues()) {
            return $this->view->setVars([
                'error' => 'error',
            ]);
        }

        $draw = $this->lotteryService->getNextDateDrawByLottery('SuperEnalotto');
        
        /** @var SuperEnalottoDraw $SuperEnalottoDraw */
        $SuperEnalottoDraw = $draw_result->getValues();

        
        $breakDownDTO = new SuperEnalottoDrawBreakDownDTO($SuperEnalottoDraw->getBreakDown());
        $break_down_list = $this->convertCurrency($breakDownDTO->toArray());
        $break_down_list['category_one']['name'] = 'match-6';
        $break_down_list['category_one']['numbers_corrected'] = 6;
        $break_down_list['category_one']['stars_corrected'] = 0;

        $this->tag->prependTitle($this->languageService->translate('resultsdate_ms_name') . $this->languageService->translate($date->format('l')) . ', ' . $date->format($this->languageService->translate('dateformat')));
        MetaDescriptionTag::setDescription($this->languageService->translate('resultsdate_ms_desc'));

        $regularNumbers = $SuperEnalottoDraw->getResult()->getRegularNumbersArray();
        $jolly_numbers[] = $SuperEnalottoDraw->getResult()->getLuckyNumbersArray()[0];
        $regularNumbers[] = $SuperEnalottoDraw->getResult()->getLuckyNumbersArray()[1];

        $this->view->pick('/numbers/past-draw');
        return $this->view->setVars([
            'break_downs' => !empty($break_down_list) ? $break_down_list : '',
            'id_draw' => $SuperEnalottoDraw->getId(),
            'last_result' => ['regular_numbers' => $regularNumbers],
            'jolly_numbers' => $jolly_numbers,
            'last_draw_date' => $SuperEnalottoDraw->getDrawDate()->format('D, d M Y'),
            'date_canonical' => $SuperEnalottoDraw->getDrawDate()->format('Y-m-d'),
            'date_draw' => $this->lotteryService->getNextDateDrawByLottery('SuperEnalotto')->modify('-1 hours')->format('Y-m-d H:i:s'),
            'symbol' => $this->userPreferencesService->getMyCurrencyNameAndSymbol()['symbol'],
            'show_s_days' => (new \DateTime())->diff($this->lotteryService->getNextDateDrawByLottery('SuperEnalotto')->modify('-1 hours'))->format('%a'),
            'actual_year' => $actualDate->format('Y'),
            'pageController' => 'superenalottoNumbersIndex',
            'draw_day' => $SuperEnalottoDraw->getDrawDate()->format('l'),
            'next_draw_date_format' => $draw->format($this->languageService->translate('dateformat')),
            'past_draw_date_format' => $SuperEnalottoDraw->getDrawDate()->format('d.m.Y'),
        ]);

    }

    public function pastListAction()
    {

        $webLanguageStrategy = new WebLanguageStrategy($this->session,$this->di->get('request'));
        $result = $this->lotteryService->getLotteryDrawsDTO('SuperEnalotto', 1000, $webLanguageStrategy);
        if (!$result->success()) {
            return $this->view->setVars([
                'error' => $result->errorMessage()
            ]);
        }
        $jackpot = $this->userPreferencesService->getJackpotInMyCurrencyAndMillions($this->lotteryService->getNextJackpot('SuperEnalotto'));
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
        $this->tag->prependTitle($this->languageService->translate('resultshist_ms_name'));
        MetaDescriptionTag::setDescription($this->languageService->translate('resultshist_ms_desc'));

        $this->view->pick('numbers/past');

        return $this->view->setVars([
            'date_draw' => $this->lotteryService->getNextDateDrawByLottery('SuperEnalotto')->modify('-1 hours')->format('Y-m-d H:i:s'),
            'date_canonical' => $this->lotteryService->getNextDateDrawByLottery('SuperEnalotto')->modify('-1 hours')->format('Y-m-d'),
            'show_s_days' => (new \DateTime())->diff($this->lotteryService->getNextDateDrawByLottery('SuperEnalotto')->modify('-1 hours'))->format('%a'),
            'symbol' => $this->userPreferencesService->getMyCurrencyNameAndSymbol()['symbol'],
            'list_draws' => $result->getValues(),
            'pageController' => 'superenalottoNumbersPast',
        ]);
    }

    public function pastResultAction()
    {
        $params = $this->router->getParams();
        if (!isset($params['date'])) {
            return $this->response->redirect('/'.$this->lottery . '/results');
        }
        $jackpot = $this->userPreferencesService->getJackpotInMyCurrencyAndMillions($this->lotteryService->getNextJackpot('SuperEnalotto'));
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
        $lotteryName = 'SuperEnalotto';
        $actualDate = new \DateTime();
        $date = empty($date) ? new \DateTime() : new \DateTime($date);
        $draw_result = $this->lotteryService->getDrawWithBreakDownByDate($lotteryName, $date);

        if (!$draw_result->getValues()) {
            return $this->response->redirect('/'.$this->lottery . '/results');
        }

        $draw = $this->lotteryService->getNextDateDrawByLottery('SuperEnalotto');
        
        /** @var SuperEnalottoDraw $SuperEnalottoDraw */
        $SuperEnalottoDraw = $draw_result->getValues();
        $regularNumbers = $SuperEnalottoDraw->getResult()->getRegularNumbersArray();
        $regularNumbers[] = $SuperEnalottoDraw->getResult()->getLuckyNumbersArray()[1];
        
        $breakDownDTO = new SuperEnalottoDrawBreakDownDTO($SuperEnalottoDraw->getBreakDown());
        $break_down_list = $this->convertCurrency($breakDownDTO->toArray());
        $break_down_list['category_one']['name'] = 'match-6';
        $break_down_list['category_one']['numbers_corrected'] = 6;
        $break_down_list['category_one']['stars_corrected'] = 0;

        $this->tag->prependTitle($this->languageService->translate('resultsdate_ms_name') . $this->languageService->translate($date->format('l')) . ', ' . $date->format($this->languageService->translate('dateformat')));
        MetaDescriptionTag::setDescription($this->languageService->translate('resultsdate_ms_desc'));

        $this->view->pick('/numbers/past-draw');
        return $this->view->setVars([
            'break_downs' => !empty($break_down_list) ? $break_down_list : '',
            'id_draw' => $SuperEnalottoDraw->getId(),
            'last_result' => ['regular_numbers' => $regularNumbers],
            'last_draw_date' => $SuperEnalottoDraw->getDrawDate()->format('D, d M Y'),
            'date_canonical' => $SuperEnalottoDraw->getDrawDate()->format('Y-m-d'),
            'date_draw' => $this->lotteryService->getNextDateDrawByLottery('SuperEnalotto')->modify('-1 hours')->format('Y-m-d H:i:s'),
            'symbol' => $this->userPreferencesService->getMyCurrencyNameAndSymbol()['symbol'],
            'show_s_days' => (new \DateTime())->diff($this->lotteryService->getNextDateDrawByLottery('SuperEnalotto')->modify('-1 hours'))->format('%a'),
            'actual_year' => $actualDate->format('Y'),
            'pageController' => 'superenalottoNumbersPast',
            'draw_day' => $SuperEnalottoDraw->getDrawDate()->format('l'),
            'next_draw_date_format' => $draw->format($this->languageService->translate('dateformat')),
            'past_draw_date_format' => $SuperEnalottoDraw->getDrawDate()->format('d.m.Y'),
        ]);

    }

    private function convertCurrency(array $break_downs)
    {
        $user_currency = $this->userPreferencesService->getCurrency();
        if (!empty($break_downs)) {
            foreach ($break_downs as &$breakDown) {
                $breakDown['SuperEnalottoPrize'] = $this->currencyConversionService->convert(new Money((int)$breakDown['lottery_prize'], new Currency('EUR')), $user_currency)->getAmount() / 10000;
            }
        }
        return $break_downs;
    }
}