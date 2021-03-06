<?php
namespace EuroMillions\shared\components\restrictedAccessStrategies;

use EuroMillions\shared\interfaces\IRestrictedAccessStrategy;
use EuroMillions\shared\dto\RestrictedAccessConfig;
use Phalcon\Http\Request;

class RestrictionByIp implements IRestrictedAccessStrategy
{
    public function isRestricted(Request $request, RestrictedAccessConfig $restrictedAccessConfig)
    {
        $allowed_ips = $restrictedAccessConfig->getAllowedIps();
        if (count($allowed_ips)) {
            return !in_array($request->getClientAddress(true), $allowed_ips, false);
        } else {
            return false;
        }
    }
}