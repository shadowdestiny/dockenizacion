<?php


namespace EuroMillions\web\vo\dto;


use EuroMillions\web\entities\User;
use EuroMillions\web\interfaces\IDto;
use EuroMillions\web\vo\dto\base\DTOBase;

class UserDTO extends DTOBase implements IDto
{
/** EMTD @rmrbest Why there are 2 UserDTO objects? (web and shared). Please, leave only one. */
    /** @var User $user */
    private $user;

    public $userId;
    public $name;
    public $surname;
    public $email;
    public $city;
    public $zip;
    public $street;
    public $phone_number;
    public $country;
    public $bankName;
    public $bankAccount;
    public $bankSwift;
    public $ip;
    public $default_language;

    public function __construct(User $user)
    {
        $this->user=$user;
        $this->exChangeObject();
    }

    public function exChangeObject()
    {
        $this->name = $this->user->getName();
        $this->surname = ($this->user->getSurname()) ? $this->user->getSurname() : '';
        $this->email = $this->user->getEmail()->toNative();
        $this->city = $this->user->getCity();
        $this->zip = $this->user->getZip();
        $this->street = $this->user->getStreet();
        $this->phone_number = $this->user->getPhoneNumber();
        $this->country = $this->user->getCountry();
        $this->bankName = $this->user->getBankName();
        $this->bankAccount = $this->user->getBankAccount();
        $this->bankSwift = $this->user->getBankSwift();
        $this->userId = $this->user->getId();
        $this->ip = $this->user->getIpAddress()->toNative();
        $this->default_language = $this->user->getDefaultLanguage();

    }

    public function toArray()
    {
        // TODO: Implement toArray() method.
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getPhoneNumber()
    {
        return $this->phone_number;
    }

    /**
     * @param mixed $phone_number
     */
    public function setPhoneNumber($phone_number)
    {
        $this->phone_number = $phone_number;
    }

    /**
     * @return mixed
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param mixed $street
     */
    public function setStreet($street)
    {
        $this->street = $street;
    }

    /**
     * @return mixed
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * @param mixed $zip
     */
    public function setZip($zip)
    {
        $this->zip = $zip;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return mixed
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }


    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @return mixed
     */
    public function getDefaultLanguage()
    {
        return $this->default_language;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }
}