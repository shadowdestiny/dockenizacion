<?php
namespace EuroMillions\vo;

class ServiceActionResult
{
    private $success;
    private $errorMessage;

    public function __construct($success, $errorMessage = null)
    {
        $this->success = $success;
        $this->errorMessage = $errorMessage;
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
        return $this->errorMessage;
    }
}
