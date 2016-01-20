<?php
namespace EuroMillions\shared\interfaces;

interface IResult
{
    /**
     * @return bool
     */
    public function success();

    /**
     * @return string
     */
    public function errorMessage();

    /**
     * @return mixed
     */
    public function returnValues();

}