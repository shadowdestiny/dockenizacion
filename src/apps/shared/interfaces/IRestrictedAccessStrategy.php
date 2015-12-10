<?php
namespace EuroMillions\shared\interfaces;
use EuroMillions\shared\dto\RestrictedAccessConfig;
use Phalcon\Http\Request;

interface IRestrictedAccessStrategy
{
    public function isRestricted(Request $request, RestrictedAccessConfig $restrictedAccessConfig);
}