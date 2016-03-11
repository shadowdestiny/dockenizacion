<?php
namespace EuroMillions\shared\components;

use EuroMillions\shared\interfaces\ISession;
use Phalcon\Session\Adapter\Files;

class PhalconSessionWrapper extends Files implements ISession{}