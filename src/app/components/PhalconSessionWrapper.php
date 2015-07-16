<?php
namespace EuroMillions\components;

use EuroMillions\interfaces\ISession;
use Phalcon\Session\Adapter\Files;

class PhalconSessionWrapper extends Files implements ISession{}