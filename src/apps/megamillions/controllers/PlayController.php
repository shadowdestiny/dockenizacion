<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 9/10/18
 * Time: 12:51
 */

namespace EuroMillions\megamillions\controllers;


final class PlayController extends \EuroMillions\shared\controllers\PlayController
{

    public function indexAction()
    {
        parent::indexAction();
        return $this->view->setVars([
            'play_dates' => $this->play_dates,
            'next_draw' => $this->dayOfWeek,
            'next_draw_format' => $this->draw->format('l j M G:i'),
            'currency_symbol' => $this->currencySymbol,
            'openTicket' => ($this->checkOpenTicket) ? '1' : '0',
            'single_bet_price' => $this->singleBetPriceCurrency->getAmount() / 100,
            'bet_price' => $this->betValue,
            'automatic_random' => isset($automatic_random) ? true : false,
            'discount_lines_title' => 'Choose your bundle',
            'discount_lines' => json_encode($this->bundlePriceDTO),
            'draws_number' => $this->bundlePriceDTO->bundlePlayDTOActive->getDraws(),
            'discount' => $this->bundlePriceDTO->bundlePlayDTOActive->getDiscount(),
            'pageController' => 'euroPlay',
            'next_draw_date_format' => $this->draw->format($this->languageService->translate('dateformat')),
            'draw_day' => $this->languageService->translate($this->draw->format('l')),
        ]);
    }

}