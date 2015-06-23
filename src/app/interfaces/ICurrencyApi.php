<?php
namespace EuroMillions\interfaces;
interface ICurrencyApi
{
    public function getRates(array $currencies);
}