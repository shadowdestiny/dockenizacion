<?php
namespace EuroMillions\sharecomponents;

use EuroMillions\shareconfig\interfaces\IRequest;
use Phalcon\Http\Request;

class PhalconRequestWrapper extends Request implements IRequest{}