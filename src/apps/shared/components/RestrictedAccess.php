<?php
namespace EuroMillions\shared\components;

use EuroMillions\shared\components\interfaces\IRestrictedAccessStrategy;
use EuroMillions\shared\dto\RestrictedAccessConfig;
use Phalcon\Http\Request;

class RestrictedAccess
{
    public function isRestricted(IRestrictedAccessStrategy $strategy, Request $request, RestrictedAccessConfig $restrictedAccessConfig)
    {
        return $strategy->isRestricted($request, $restrictedAccessConfig);
    }
}