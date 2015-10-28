<?php
namespace EuroMillions\web\vo;

use EuroMillions\web\interfaces\IQueryString;
use EuroMillions\web\vo\base\NullValue;

class NullQueryString extends NullValue implements IQueryString
{
    public function __construct()
    {
        parent::__construct('');
    }
}