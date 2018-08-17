<?php

namespace EuroMillions\web\interfaces;


interface IHandlerPaymentGateway
{

    public function call($data);
}