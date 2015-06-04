<?php
namespace EuroMillions\services\external_apis;

use EuroMillions\entities\Lottery;
use EuroMillions\interfaces\IJackpotApi;

class LotteryApisFactory
{
    /**
     * @param Lottery $lottery
     * @return IJackpotApi
     */
    public function jackpotApi(Lottery $lottery)
    {
        $object_name = '\EuroMillions\services\external_apis\\'.$lottery->getJackpotApi();
        return new $object_name();
    }
}