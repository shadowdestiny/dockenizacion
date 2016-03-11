<?php
namespace EuroMillions\shared\components\restrictedAccessStrategies;
use EuroMillions\shared\interfaces\IRestrictedAccessStrategy;
use EuroMillions\shared\dto\RestrictedAccessConfig;
use Phalcon\Http\Request;

class RestrictionByIpAndHttpAuth implements IRestrictedAccessStrategy
{

    protected $restrictionByIp;
    protected $restrictionByHttpAuth;

    public function __construct(RestrictionByIp $restrictionByIp = null, RestrictionByHttpAuth $restrictionByHttpAuth = null)
    {
        $this->restrictionByIp = ($restrictionByIp) ?: new RestrictionByIp();
        $this->restrictionByHttpAuth = ($restrictionByHttpAuth) ?: new RestrictionByHttpAuth();
    }

    public function isRestricted(Request $request, RestrictedAccessConfig $restrictedAccessConfig)
    {
        if ($this->restrictionByIp->isRestricted($request, $restrictedAccessConfig)) {
            return $this->restrictionByHttpAuth->isRestricted($request, $restrictedAccessConfig);
        } else {
            return false;
        }
    }
}