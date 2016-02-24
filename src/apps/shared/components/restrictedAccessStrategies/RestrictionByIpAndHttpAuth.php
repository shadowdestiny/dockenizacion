<?php
namespace EuroMillions\shared\components\restrictedAccessStrategies;
use EuroMillions\shared\interfaces\IRestrictedAccessStrategy;
use EuroMillions\shared\dto\RestrictedAccessConfig;
use Phalcon\Http\Request;

class RestrictionByIpAndHttpAuth implements IRestrictedAccessStrategy
{
    public function isRestricted(Request $request, RestrictedAccessConfig $restrictedAccessConfig)
    {
        $byIp = new RestrictionByIp();
        if ($byIp->isRestricted($request, $restrictedAccessConfig)) {
            $byAuth = new RestrictionByHttpAuth();
            return $byAuth->isRestricted($request, $restrictedAccessConfig);
        } else {
            return false;
        }
    }
}