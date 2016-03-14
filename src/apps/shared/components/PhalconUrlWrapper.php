<?php
namespace EuroMillions\shared\components;

use EuroMillions\shared\interfaces\IUrlManager;
use Phalcon\Mvc\Url;

class PhalconUrlWrapper extends Url implements IUrlManager{}