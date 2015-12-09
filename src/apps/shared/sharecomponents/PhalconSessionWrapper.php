<?php
namespace EuroMillions\shared\sharecomponents;

use EuroMillions\shared\shareconfig\interfaces\ISession;
use Phalcon\Session\Adapter\Files;

class PhalconSessionWrapper extends Files implements ISession{}