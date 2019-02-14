<?php
namespace EuroMillions\web\entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use EuroMillions\shared\vo\Wallet;
use EuroMillions\shared\vo\Winning;
use EuroMillions\web\interfaces\IEntity;
use EuroMillions\web\interfaces\IUser;
use EuroMillions\web\vo\Email;
use EuroMillions\web\vo\IPAddress;
use EuroMillions\web\vo\Password;
use EuroMillions\web\vo\RememberToken;
use EuroMillions\web\vo\ValidationToken;
use EuroMillions\web\vo\BirthDate;
use Money\Currency as MoneyCurrency;
use Money\Money;
use Money\UnknownCurrencyException;

class User extends EntityBase implements IEntity, IUser, \JsonSerializable
{
    public static $COUNTRIES_NO_VALIDATION = ['140','13'];

    protected $id;
    protected $name;
    protected $surname;
    /** @var  Password */
    protected $password;
    /** @var  Email */
    protected $email;
    /** @var  RememberToken */
    protected $rememberToken;
    /** @var  Wallet */
    protected $wallet;
    /** @var  MoneyCurrency */
    protected $user_currency;
    protected $country;
    protected $validated;
    /** @var  ValidationToken */
    protected $validationToken;
    private $playConfig;
    protected $street;
    protected $zip;
    protected $city;
    protected $phone_number;
    /**@var BirthDate */
    protected $birth_date;
    protected $jackpotReminder;
    protected $threshold;
    protected $userNotification;
    protected $show_modal_winning;
    /** @var  Money */
    protected $winning_above;
    protected $bankName;
    protected $bankAccount;
    protected $bankSwift;
    protected $bankUserName;
    protected $bankSurname;
    protected $created;
    /** @var IPAddress */
    protected $ip_address;
    protected $lastConnection;
    protected $defaultLanguage;
    protected $disabledDate;

    public function __construct(){
        $this->playConfig = new ArrayCollection();
        $this->userNotification = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getWinningAbove()
    {
        return $this->winning_above;
    }

    /**
     * @param mixed $winning_above
     */
    public function setWinningAbove($winning_above)
    {
        $this->winning_above = $winning_above;
    }

    /**
     * @return ArrayCollection
     */
    public function getPlayConfig()
    {
        return $this->playConfig;
    }

    /**
     * @param ArrayCollection $playConfig
     */
    public function setPlayConfig($playConfig)
    {
        $this->playConfig = $playConfig;
    }

    /**
     * @return ArrayCollection
     */
    public function getUserNotification()
    {
        return $this->userNotification;
    }

    /**
     * @param ArrayCollection $userNotification
     */
    public function setUserNotification($userNotification)
    {
        $this->userNotification = $userNotification;
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

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
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

    public function getBalance()
    {
        return $this->wallet->getBalance();
    }

    public function setWallet(Wallet $wallet)
    {
        $this->wallet = $wallet;
    }

    public function setValidated($validated)
    {
        $this->validated = $validated;
    }

    public function getValidated()
    {
        if($this->isValidationDisabled()) return true;
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

    public function getBirthDate()
    {
        return $this->birth_date;
    }

    public function setBirthDate(BirthDate $birth_date)
    {
        $this->birth_date = $birth_date;
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
        return $this->jackpotReminder;
    }

    /**
     * @param mixed $jackpotReminder
     */
    public function setJackpotReminder($jackpotReminder)
    {
        $this->jackpotReminder = $jackpotReminder;
    }

    /**
     * @return MoneyCurrency
     * @throws UnknownCurrencyException
     */
    public function getUserCurrency()
    {
        if (!$this->user_currency) {
            $this->user_currency = new MoneyCurrency('EUR');
        }
        return $this->user_currency;
    }

    public function setUserCurrency(MoneyCurrency $user_currency)
    {
        $this->user_currency = $user_currency;
    }

    public function pay(Money $amount)
    {
        $this->wallet = $this->wallet->pay($amount);
    }

    public function awardPrize(Winning $winning)
    {  if(!$winning->greaterThanOrEqualThreshold())
        {
            $this->wallet = $this->wallet->award($winning->getPrice());
            $this->setShowModalWinning(true);
        }
    }

    public function reChargeWallet(Money $amount)
    {
        $this->wallet = $this->wallet->upload($amount);
    }

    public function reChargeSubscriptionWallet(Money $amount)
    {
        $this->wallet = $this->wallet->uploadToSubscription($amount);
    }

    public function removeSubscriptionWallet(Money $amount)
    {
        $this->wallet = $this->wallet->payWithSubscription($amount);
    }
    
    public function removeSubscriptionWithWallet(Money $amount)
    {
        $this->wallet = $this->wallet->paySubscriptionWithWallet($amount);
    }
    
    public function removeWalletToSubscription(Money $amount)
    {
        $this->wallet = $this->wallet->removeWalletToSubscription($amount);
    }

    /**
     * @return Wallet
     */
    public function getWallet()
    {
        return $this->wallet;
    }

    /**
     * @return mixed
     */
    public function getShowModalWinning()
    {
        return $this->show_modal_winning;
    }

    /**
     * @param mixed $show_modal_winning
     */
    public function setShowModalWinning($show_modal_winning)
    {
        $this->show_modal_winning = $show_modal_winning;
    }

    public function toJsonData()
    {
        return json_encode($this);
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
        ];
    }

    public function getLocale()
    {
        return 'en_GB';
    }

    /**
     * @return mixed
     */
    public function getBankName()
    {
        return $this->bankName;
    }

    /**
     * @param mixed $bankName
     */
    public function setBankName($bankName)
    {
        $this->bankName = $bankName;
    }

    /**
     * @return mixed
     */
    public function getBankAccount()
    {
        return $this->bankAccount;
    }

    /**
     * @param mixed $bankAccount
     */
    public function setBankAccount($bankAccount)
    {
        $this->bankAccount = $bankAccount;
    }

    /**
     * @return mixed
     */
    public function getBankSwift()
    {
        return $this->bankSwift;
    }

    /**
     * @param mixed $bankSwift
     */
    public function setBankSwift($bankSwift)
    {
        $this->bankSwift = $bankSwift;
    }

    /**
     * @return mixed
     */
    public function getBankUserName()
    {
        return $this->bankUserName;
    }

    /**
     * @param mixed $bankUserName
     */
    public function setBankUserName($bankUserName)
    {
        $this->bankUserName = $bankUserName;
    }

    /**
     * @return mixed
     */
    public function getBankSurname()
    {
        return $this->bankSurname;
    }

    /**
     * @param mixed $bankSurname
     */
    public function setBankSurname($bankSurname)
    {
        $this->bankSurname = $bankSurname;
    }

    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param mixed $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @param IPAddress $value
     */
    public function setIpAddress(IPAddress $value)
    {
        $this->ip_address = $value;
    }

    /**
     * @return IPAddress
     */
    public function getIpAddress()
    {
        return $this->ip_address;
    }

    /**
     * @return mixed
     */
    public function getLastConnection()
    {
        return $this->lastConnection;
    }

    /**
     * @param $lastConnection
     */
    public function setLastConnection($lastConnection)
    {
        $this->lastConnection = $lastConnection;
    }

    /**
     * @return string
     */
    public function getDefaultLanguage()
    {
        return $this->defaultLanguage;
    }

    /**
     * @param $defaultLanguage
     */
    public function setDefaultLanguage($defaultLanguage)
    {
        $this->defaultLanguage = $defaultLanguage;
    }

    /**
     * @return mixed
     */
    public function getDisabledDate()
    {
        return $this->disabledDate;
    }

    /**
     * @param mixed $disabledDate
     */
    public function setDisabledDate($disabledDate)
    {
        $this->disabledDate = $disabledDate;
    }

    public function isValidationDisabled()
    {
        return in_array($this->getCountry(),self::$COUNTRIES_NO_VALIDATION);
    }

    /**
     * @param \DateTime $date
     * @return array
     */
    public function getPlayConfigsFilteredForNextDraw(\DateTime $date)
    {
        $criteria = Criteria::create()->where(Criteria::expr()->eq('active', 1))
            ->andWhere(Criteria::expr()->lt('startDrawDate', $date))
            ->andWhere(Criteria::expr()->gt('lastDrawDate', $date))
            ->orWhere(Criteria::expr()->eq('startDrawDate', $date))
            ->orWhere(Criteria::expr()->eq('lastDrawDate', $date));

        return $this->getPlayConfig()->matching($criteria);
    }
}