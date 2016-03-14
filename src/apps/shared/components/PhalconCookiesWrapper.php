<?php
namespace EuroMillions\shared\components;

use EuroMillions\shared\interfaces\ICookieManager;
use Phalcon\Http\Response\Cookies;

class PhalconCookiesWrapper extends Cookies implements ICookieManager{}