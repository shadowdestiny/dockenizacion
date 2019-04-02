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

class ChasierDTO extends DTOBase implements IDto
{
    public $transactionID;

    public $cashierUrl;

    public $type;

    public $error;

    public $message;

    public function __construct($type, array $data = null, $transactionID = null, $error = false, $message = "")
    {
        if ($data == null) {
            $this->cashierUrl = null;
            $this->transactionID = $transactionID;
        } else {
            $this->transactionID = $transactionID;
            $this->guard($data);
        }

        $this->error = $error;
        $this->message = $message;

        if ($this->error == false) {
            $this->type = $type->value();
        }
    }


    private function guard(array $data)
    {
        if (!isset($data['cashierUrl'])) {
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
