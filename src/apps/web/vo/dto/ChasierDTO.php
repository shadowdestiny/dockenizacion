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

    public $type;

    public function __construct(array $data=null, $transactionID=null,$message = "", $type)
    {
        if($data == null)
        {
            $this->cashierUrl = null;
            $this->transactionID = $transactionID;
        } else {
            $this->transactionID = $transactionID;
            $this->guard($data);
        }
        $this->message = $message;
        $this->type = $type->value();
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

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }
}