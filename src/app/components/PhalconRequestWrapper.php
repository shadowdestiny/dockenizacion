<?php
namespace EuroMillions\components;

use EuroMillions\interfaces\IRequest;
use Phalcon\Http\Request;

class PhalconRequestWrapper extends Request implements IRequest{}