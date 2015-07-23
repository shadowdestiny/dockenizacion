<?php
namespace EuroMillions\components;

use EuroMillions\interfaces\ICookieManager;
use Phalcon\Http\Response\Cookies;

class PhalconCookiesWrapper extends Cookies implements ICookieManager{}