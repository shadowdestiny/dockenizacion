<?php


namespace EuroMillions\vo\enum;


class ValidationOperationType extends \SplEnum
{

    /** Bet validation request */
    const TYPE_ONE = 1;
    /** Bet validation confirmation */
    const TYPE_TWO = 2;
    /**  Prize confirmation */
    const TYPE_THREE = 3;
    /** Loteria nacional stock */
    const TYPE_FOUR = 4;
    /** Loteria nacional reservation */
    const TYPE_FIVE = 5;
    /** Acknowledge */
    const TYPE_SIX = 6;

}