<?php


namespace EuroMillions\admin\vo\dto;


use EuroMillions\web\entities\User;
use EuroMillions\admin\interfaces\IDto;
use EuroMillions\admin\vo\dto\base\DTOBase;

class UserDTO extends DTOBase implements IDto
{

    /** @var User $user */
    private $user;

    public $id;
    public $name;
    public $surname;
    public $email;
    public $city;
    public $zip;
    public $street;
    public $phone_number;
    public $country;
    public $balance;

    public function __construct(User $user)
    {
        $this->user=$user;
        $this->exChangeObject();
    }

    public function exChangeObject()
    {
        $this->id = $this->user->getId()->id();
        $this->name = $this->user->getName();
        $this->surname = ($this->user->getSurname()) ? $this->user->getSurname() : '';
        $this->email = $this->user->getEmail()->toNative();
        $this->city = $this->user->getCity();
        $this->zip = $this->user->getZip();
        $this->street = $this->user->getStreet();
        $this->phone_number = $this->user->getPhoneNumber();
        $this->country = $this->user->getCountry();
        $this->balance = $this->user->getBalance()->getAmount();
    }

    public function toArray()
    {
        return $array = json_decode(json_encode($this),TRUE);
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

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }
}