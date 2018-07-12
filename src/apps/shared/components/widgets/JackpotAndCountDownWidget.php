<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 10/07/18
 * Time: 14:53
 */

namespace EuroMillions\shared\components\widgets;

use EuroMillions\web\interfaces\IJackpot;
use Phalcon\Mvc\View\Simple as ViewSimple;
use Phalcon\Mvc\ViewInterface;

class JackpotAndCountDownWidget extends \Phalcon\Mvc\User\Component
{


    protected $jackpot;

    protected $countDown;

    public function __construct(IJackpot $jackpot, $countDown)
    {
        $this->jackpot = $jackpot;
        $this->countDown = $countDown;
    }

    public function render()
    {
        try {


            return $this->getView()->render('_elements/pagination',[]);
        } catch (\Exception $exc) {

        }
    }


}