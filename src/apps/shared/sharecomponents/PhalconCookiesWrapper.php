<?php
namespace EuroMillions\sharecomponents;

use EuroMillions\shareconfig\interfaces\ICookieManager;
use Phalcon\Http\Response\Cookies;

class PhalconCookiesWrapper extends Cookies implements ICookieManager{}