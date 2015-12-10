<?php


namespace EuroMillions\shared\dto;

use EuroMillions\shared\vo\HttpUser;

class RestrictedAccessConfig
{
    private $allowedIps;
    private $allowedHttpUser;

    public function __construct(array $properties = [])
    {
        foreach($properties as $property_name => $property_value) {
            $setter_method = 'set'.ucfirst($property_name);
            $this->$setter_method($property_value);
        }
    }

    public function setAllowedIps($commaDelimitedIps)
    {
        $this->allowedIps = explode(',', $commaDelimitedIps);
    }

    public function setAllowedHttpUser(HttpUser $user)
    {
        $this->allowedHttpUser = $user;
    }

    /**
     * @return mixed
     */
    public function getAllowedIps()
    {
        return $this->allowedIps;
    }

    /**
     * @return HttpUser
     */
    public function getAllowedHttpUser()
    {
        return $this->allowedHttpUser;
    }

}