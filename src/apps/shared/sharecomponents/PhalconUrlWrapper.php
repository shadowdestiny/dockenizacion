<?php
namespace EuroMillions\shared\sharecomponents;

use EuroMillions\shared\shareconfig\interfaces\IUrlManager;
use Phalcon\Mvc\Url;

class PhalconUrlWrapper extends Url implements IUrlManager{}