<?php
namespace EuroMillions\shared\components;

use EuroMillions\shared\components\shareInterfaces\IRestrictedAccessStrategy;
use EuroMillions\shared\shareDTO\RestrictedAccessConfig;
use Phalcon\Http\Request;

class RestrictedAccess
{
    public function isRestricted(IRestrictedAccessStrategy $strategy, Request $request, RestrictedAccessConfig $restrictedAccessConfig)
    {
        return $strategy->isRestricted($request, $restrictedAccessConfig);
    }
}