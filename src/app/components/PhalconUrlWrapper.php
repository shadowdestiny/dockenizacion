<?php
namespace EuroMillions\components;

use EuroMillions\interfaces\IUrlManager;
use Phalcon\Mvc\Url;

class PhalconUrlWrapper extends Url implements IUrlManager{}