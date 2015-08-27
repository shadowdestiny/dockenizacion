<?php
namespace EuroMillions\entities;

use EuroMillions\interfaces\IEntity;
use EuroMillions\interfaces\IUser;
use EuroMillions\vo\Email;
use EuroMillions\vo\Password;
use EuroMillions\vo\RememberToken;
use EuroMillions\vo\UserId;
use Money\Money;
use Rhumsaa\Uuid\Uuid;

class User extends EntityBase implements IEntity, IUser
{
    /** @var  Uuid */
    protected $id;
    protected $name;
    protected $surname;
    /** @var  Password */
    protected $password;
    /** @var  Email */
    protected $email;
    /** @var  RememberToken */
    protected $rememberToken;
    /** @var  Money */
    protected $balance;

    protected $country;
    protected $validated;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param mixed $surname
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
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

    public function setId(UserId $id)
    {
        $this->id = (string)$id;
    }

    /**
     * @return UserId
     */
    public function getId()
    {
        return new UserId($this->id);
    }

    /**
     * @return Password
     */
    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword(Password $password)
    {
        $this->password = $password;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setRememberToken($agentIdentificationString)
    {
        $this->rememberToken = new RememberToken($this->email->toNative(), $this->password->toNative(), $agentIdentificationString);
    }

    public function getRememberToken()
    {
        return $this->rememberToken;
    }

    public function setBalance(Money $balance)
    {
        $this->balance = $balance;
    }

    public function getBalance()
    {
        return $this->balance;
    }

    public function setValidated($validated)
    {
        $this->validated = $validated;
    }

    public function getValidated()
    {
        return $this->validated;
    }
}