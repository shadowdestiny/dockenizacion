<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 10/07/18
 * Time: 14:53
 */

namespace EuroMillions\shared\components\widgets;

use Phalcon\Mvc\View\Simple as ViewSimple;
use Phalcon\Mvc\ViewInterface;

class JackpotAndCountDownWidget extends \Phalcon\Mvc\User\Component
{


    public function __construct()
    {

    }

    public function render()
    {

        try {
            return $this->getView()->render('_elements/pagination',[]);
        } catch (\Exception $exc) {

        }
    }


}