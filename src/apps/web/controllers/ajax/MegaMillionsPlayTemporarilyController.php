<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 5/11/18
 * Time: 14:34
 */

namespace EuroMillions\web\controllers\ajax;


class MegaMillionsPlayTemporarilyController extends PowerBallPlayTemporarilyController
{

    protected function redirectResult($lotteryName = 'powerball')
    {
        parent::redirectResult('megamillions'); // TODO: Change the autogenerated stub
    }


}