<?php
namespace EuroMillions\shared\components\restrictedAccessStrategies;

use EuroMillions\shared\components\shareInterfaces\IRestrictedAccessStrategy;
use EuroMillions\shared\shareDTO\RestrictedAccessConfig;
use Phalcon\Http\Request;

class RestrictionByHttpAuth implements IRestrictedAccessStrategy
{
    public function isRestricted(Request $request, RestrictedAccessConfig $restrictedAccessConfig)
    {
        $user_auth = $request->getBasicAuth();
        if (empty($user_auth)) {
            $this->askForAuth();
            exit;
        } else {
            if ($user_auth['username'] == $restrictedAccessConfig->getAllowedHttpUser()->getUsername() &&
                $user_auth['password'] == $restrictedAccessConfig->getAllowedHttpUser()->getPassword()) {
                return false;
            } else {
                $this->askForAuth();
                return true;
            }
        }
    }

    private function askForAuth()
    {
        header('WWW-Authenticate: Basic realm="euromillions.com"');
        header('HTTP/1.0 401 Unauthorized');
    }

}