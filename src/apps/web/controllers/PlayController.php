<?php

namespace EuroMillions\web\controllers;

use EuroMillions\web\components\DateTimeUtil;
use EuroMillions\web\components\tags\MetaDescriptionTag;
use EuroMillions\web\components\ViewHelper;
use EuroMillions\web\entities\User;
use Money\Currency;

class PlayController extends PublicSiteControllerBase
{
    public function indexAction()
    {

        $jackpot = $this->userPreferencesService->getJackpotInMyCurrency($this->lotteryService->getNextJackpot('EuroMillions'));
        $play_dates = $this->lotteryService->getRecurrentDrawDates('Euromillions');
        $draw = $this->lotteryService->getNextDateDrawByLottery('Euromillions');
        $date_time_util = new DateTimeUtil();
        $dayOfWeek = $date_time_util->getDayOfWeek($draw);
        $checkOpenTicket = $date_time_util->checkTimeForClosePlay($draw);
        $single_bet_price = $this->lotteryService->getSingleBetPriceByLottery('EuroMillions');
        $automatic_random = $this->request->get('random');
        $bundlePriceDTO = $this->domainServiceFactory->getPlayService()->retrieveEuromillionsBundlePriceDTO();
        if (!$this->authService->isLogged()) {
            $user_currency = $this->userPreferencesService->getCurrency();
            $single_bet_price_currency = $this->currencyConversionService->convert($single_bet_price, $user_currency);
        } else {
            $current_user_id = $this->authService->getCurrentUser()->getId();
            /** @var User $user */
            $user = $this->userService->getUser($current_user_id);
            $single_bet_price_currency = $this->currencyConversionService->convert($single_bet_price, new Currency($user->getUserCurrency()->getName()));
        }
        $currency_symbol = $this->userPreferencesService->getMyCurrencyNameAndSymbol()['symbol'];
        $this->tag->prependTitle($this->languageService->translate('play_em_name') . ViewHelper::formatJackpotNoCents($jackpot));
        MetaDescriptionTag::setDescription($this->languageService->translate('play_em_desc'));

        return $this->view->setVars([
            'jackpot_value' => ViewHelper::formatJackpotNoCents($jackpot),
            'play_dates' => $play_dates,
            'next_draw' => $dayOfWeek,
            'next_draw_format' => $draw->format('l j M G:i'),
            'currency_symbol' => $currency_symbol,
            'openTicket' => ($checkOpenTicket) ? '1' : '0',
            'single_bet_price' => $single_bet_price_currency->getAmount() / 100,
            'automatic_random' => isset($automatic_random) ? true : false,
            'discount_lines_title' => 'Choose your bundle',
            'discount_lines' => json_encode($bundlePriceDTO),
            'draws_number' => $bundlePriceDTO->bundlePlayDTOActive->getDraws(),
            'discount' => $bundlePriceDTO->bundlePlayDTOActive->getDiscount(),
            'pageController' => 'euroPlay',
        ]);
    }
}
