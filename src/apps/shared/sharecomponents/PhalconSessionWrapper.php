<?php
namespace EuroMillions\sharecomponents;

use EuroMillions\shareconfig\interfaces\ISession;
use Phalcon\Session\Adapter\Files;

class PhalconSessionWrapper extends Files implements ISession{}