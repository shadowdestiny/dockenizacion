<?php
namespace EuroMillions\shared\components\restrictedAccessStrategies;

use EuroMillions\shared\interfaces\IRestrictedAccessStrategy;
use EuroMillions\shared\dto\RestrictedAccessConfig;
use EuroMillions\web\components\ExitProcessWrapper;
use Phalcon\Http\Request;
use Phalcon\Http\Response;

class RestrictionByHttpAuth implements IRestrictedAccessStrategy
{

    protected $response;
    protected $exitProcessWrapper;

    public function __construct(Response $response = null, ExitProcessWrapper $exitProcessWrapper = null)
    {
        $this->response = ($response) ?: new Response();
        $this->exitProcessWrapper = ($exitProcessWrapper) ?: new ExitProcessWrapper();
    }


    public function isRestricted(Request $request, RestrictedAccessConfig $restrictedAccessConfig)
    {
        $user_auth = $request->getBasicAuth();
        if (empty($user_auth)) {
            $this->askForAuth();
            $this->exitProcessWrapper->finish();
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
        $this->response->setRawHeader('WWW-Authenticate: Basic realm="euromillions.com"');
        $this->response->setRawHeader('HTTP/1.0 401 Unauthorized');
//        header('WWW-Authenticate: Basic realm="euromillions.com"');
//        header('HTTP/1.0 401 Unauthorized');
    }
}