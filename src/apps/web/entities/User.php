<?php
namespace EuroMillions\web\entities;

use Doctrine\Common\Collections\ArrayCollection;
use EuroMillions\web\interfaces\IEntity;
use EuroMillions\web\interfaces\IUser;
use EuroMillions\web\vo\Email;
use EuroMillions\web\vo\Password;
use EuroMillions\web\vo\RememberToken;
use EuroMillions\web\vo\UserId;
use EuroMillions\web\vo\ValidationToken;
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
    /** @var  ValidationToken */
    protected $validationToken;

    private $paymentMethod;

    /** @var  PlayConfig */
    private $playConfig;

    protected $street;
    protected $zip;
    protected $city;
    protected $phone_number;
    protected $jackpot_reminder;
    protected $threshold;



    public function __construct(){
        $this->paymentMethod = new ArrayCollection();
        $this->playConfig = new ArrayCollection();
    }

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

    public function setValidationToken($validationToken)
    {
        $this->validationToken=$validationToken;
    }
    public function getValidationToken()
    {
        return $this->validationToken;
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
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getJackpotReminder()
    {
        return $this->jackpot_reminder;
    }

    /**
     * @param mixed $jackpot_reminder
     */
    public function setJackpotReminder($jackpot_reminder)
    {
        $this->jackpot_reminder = $jackpot_reminder;
    }

    /**
     * @return mixed
     */
    public function getThreshold()
    {
        return $this->threshold;
    }

    /**
     * @param mixed $threshold
     */
    public function setThreshold($threshold)
    {
        $this->threshold = $threshold;
    }

}