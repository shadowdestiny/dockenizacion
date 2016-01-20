<?php
namespace EuroMillions\shared\vo\results;

use EuroMillions\shared\interfaces\IResult;

abstract class ResultBase implements IResult
{
    protected $success;
    protected $errorMessage;
    protected $returnValues;

    public function __construct($success, $returnValues = null, $errorMessage = null)
    {
        $this->success = $success;
        $this->returnValues = $returnValues;
        $this->errorMessage = $errorMessage;
    }

    public function success()
    {
        return $this->success;
    }


    public function errorMessage()
    {
        return $this->errorMessage;
    }

    public function returnValues()
    {
        return $this->returnValues;
    }
}