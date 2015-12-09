<?php
namespace EuroMillions\shared\components;

use EuroMillions\shared\config\interfaces\ISession;
use Phalcon\Session\Adapter\Files;

class PhalconSessionWrapper extends Files implements ISession{}