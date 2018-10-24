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

    public $transactionID;

    public $message;

    public function __construct(array $data=null, $transactionID=null,$message = "")
    {
        if($data == null)
        {
            $this->cashierUrl = null;
            $this->transactionID = null;
        } else {
            $this->transactionID = $transactionID;
            $this->guard($data);
        }
        $this->message = $message;
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