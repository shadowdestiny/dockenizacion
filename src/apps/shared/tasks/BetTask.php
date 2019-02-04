<?php
/**
 * Created by PhpStorm.
 * User: vapdl
 * Date: 14/11/18
 * Time: 03:48 PM
 */

namespace EuroMillions\shared\tasks;

trait BetTask
{
    public function placeLotteryBets($lotteryName, $args = null)
    {
        if (!$args) {
            $date = new \DateTime();
        } else {
            $date = new \DateTime($args[0]);
        }

        $lotteries = $this->lotteryService->getLotteriesOrderedByNextDrawDate();
        /** @var Lottery $lottery */
        foreach ($lotteries as $lottery) {
            if ($lottery->getName() == $lotteryName) {
                $this->lotteryService->placeLotteryBetForNextDraw($lottery, $date);
            }
        }
    }
}