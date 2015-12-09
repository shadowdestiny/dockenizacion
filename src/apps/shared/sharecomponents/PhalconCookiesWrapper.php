<?php
namespace EuroMillions\shared\sharecomponents;

use EuroMillions\shared\shareconfig\interfaces\ICookieManager;
use Phalcon\Http\Response\Cookies;

class PhalconCookiesWrapper extends Cookies implements ICookieManager{}