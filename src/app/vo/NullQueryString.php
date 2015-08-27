<?php
namespace EuroMillions\vo;

use EuroMillions\interfaces\IQueryString;
use EuroMillions\vo\base\NullValue;

class NullQueryString extends NullValue implements IQueryString
{
    public function __construct()
    {
        parent::__construct('');
    }
}