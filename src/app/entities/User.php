<?php
namespace EuroMillions\entities;

use EuroMillions\interfaces\IEntity;
use EuroMillions\vo\Email;
use EuroMillions\vo\Password;
use EuroMillions\vo\RememberToken;
use EuroMillions\vo\Username;
use Money\Money;
use Rhumsaa\Uuid\Uuid;

class User extends EntityBase implements IEntity
{
    /** @var  Uuid */
    protected $id;
    /** @var  Username */
    protected $username;
    /** @var  Password */
    protected $password;
    /** @var  Email */
    protected $email;
    /** @var  RememberToken */
    protected $rememberToken;
    /** @var  Money */
    protected $balance;

    public function setId(Uuid $id)
    {
        $this->id = $id;
    }

    /**
     * @return Uuid
     */
    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername(Username $username)
    {
        $this->username = $username;
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
        $this->rememberToken = new RememberToken($this->username->username(), $this->password->password(), $agentIdentificationString);
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


}