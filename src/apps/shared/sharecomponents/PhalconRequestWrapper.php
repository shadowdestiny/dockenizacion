<?php
namespace EuroMillions\shared\sharecomponents;

use EuroMillions\shared\shareconfig\interfaces\IRequest;
use Phalcon\Http\Request;

class PhalconRequestWrapper extends Request implements IRequest{}