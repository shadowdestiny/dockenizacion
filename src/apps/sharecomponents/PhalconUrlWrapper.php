<?php
namespace EuroMillions\sharecomponents;

use EuroMillions\shareconfig\interfaces\IUrlManager;
use Phalcon\Mvc\Url;

class PhalconUrlWrapper extends Url implements IUrlManager{}