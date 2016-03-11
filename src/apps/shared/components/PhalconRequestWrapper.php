<?php
namespace EuroMillions\shared\components;

use EuroMillions\shared\interfaces\IRequest;
use Phalcon\Http\Request;

class PhalconRequestWrapper extends Request implements IRequest{}