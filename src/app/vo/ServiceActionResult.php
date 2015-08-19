<?php
namespace EuroMillions\vo;

class ServiceActionResult
{
    private $success;
    private $returnValues;

    public function __construct($success, $returnValues = null)
    {
        $this->success = $success;
        $this->returnValues = $returnValues;
    }

    /**
     * @return boolean
     */
    public function success()
    {
        return $this->success;
    }

    /**
     * @return string
     */
    public function errorMessage()
    {
        return $this->returnValues;
    }

    public function getValues()
    {
        return $this->returnValues;
    }
}
