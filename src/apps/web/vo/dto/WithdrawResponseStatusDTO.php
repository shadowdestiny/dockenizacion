<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 8/11/18
 * Time: 12:55
 */

namespace EuroMillions\web\vo\dto;


class WithdrawResponseStatusDTO implements \JsonSerializable
{

    public $transactionCode;

    public $status;

    public $signature;


    public function __construct($data)
    {
        $this->transactionCode= $data['transactionCode'];
        $this->status= $data['status'];
        $this->signature= $data['signature'];
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            "TransactionCode" => $this->transactionCode,
            "Status" => $this->status,
            "Signature" => $this->signature
        ];
    }
}