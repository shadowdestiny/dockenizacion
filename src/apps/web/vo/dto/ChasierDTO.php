<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 18/08/18
 * Time: 14:53
 */

namespace EuroMillions\web\vo\dto;


use EuroMillions\web\interfaces\IDto;
use EuroMillions\web\vo\dto\base\DTOBase;

class ChasierDTO  extends DTOBase implements IDto
{

    public $cashierUrl;

    public function __construct(array $data=null)
    {
        if($data == null)
        {
            $this->cashierUrl = null;
        } else {
            $this->guard($data);
        }
    }


    private function guard(array $data)
    {
        if(!isset($data['cashierUrl']))
        {
            $this->cashierUrl = null;
        } else {
            $this->cashierUrl = $data['cashierUrl'];
        }
    }

    public function toArray()
    {
        // TODO: Implement toArray() method.
    }

    public function exChangeObject()
    {
        // TODO: Implement exChangeObject() method.
    }
}