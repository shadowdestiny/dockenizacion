<?php
namespace EuroMillions\shared\interfaces;
use EuroMillions\shared\dto\RestrictedAccessConfig;
use Phalcon\Http\Request;

interface IRestrictedAccessStrategy
{
    /**
     * @param Request $request
     * @param RestrictedAccessConfig $restrictedAccessConfig
     * @return bool
     */
    public function isRestricted(Request $request, RestrictedAccessConfig $restrictedAccessConfig);
}