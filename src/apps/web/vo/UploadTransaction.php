<?php


namespace EuroMillions\web\vo;


use EuroMillions\web\entities\Transaction;

class UploadTransaction extends Transaction
{
    protected $data;
    private $amountUploaded;

    public function __construct()
    {

    }

    protected function toString()
    {
        // TODO: Implement toString() method.
    }
}