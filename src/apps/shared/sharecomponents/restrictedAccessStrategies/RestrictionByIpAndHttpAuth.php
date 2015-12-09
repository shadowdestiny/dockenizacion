<?php
namespace EuroMillions\shared\sharecomponents\restrictedAccessStrategies;
use EuroMillions\shared\sharecomponents\shareInterfaces\IRestrictedAccessStrategy;
use EuroMillions\shared\shareDTO\RestrictedAccessConfig;
use Phalcon\Http\Request;

class RestrictionByIpAndHttpAuth implements IRestrictedAccessStrategy
{
    public function isRestricted(Request $request, RestrictedAccessConfig $restrictedAccessConfig)
    {
        $byIp = new RestrictionByIp();
        if ($byIp->isRestricted($request, $restrictedAccessConfig)) {
            $byAuth = new RestrictionByHttpAuth();
            if ($byAuth->isRestricted($request, $restrictedAccessConfig)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}