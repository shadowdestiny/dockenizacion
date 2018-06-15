<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 15/06/18
 * Time: 10:56
 */

namespace EuroMillions\web\vo;


class PowerBallDrawBreakDown extends EuroMillionsDrawBreakDown
{

    /**
     * @param array $breakdown
     */
    public function __construct(array $breakdown)
    {
        if (!is_array($breakdown)) {
            throw new \InvalidArgumentException("");
        }
        if (count($breakdown) < self::NUMBER_OF_CATEGORIES) {
            throw new \LengthException('Incorrect categories length from collection');
        }
        $this->breakdown = $breakdown;
        $this->exchangePowerBallData();
        parent::__construct($breakdown);
    }

    protected function exchangePowerBallData()
    {

    }


}