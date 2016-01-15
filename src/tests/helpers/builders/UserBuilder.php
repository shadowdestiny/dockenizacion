<?php
namespace tests\helpers\builders;

use Doctrine\Common\Collections\ArrayCollection;
use EuroMillions\shared\vo\Wallet;
use EuroMillions\web\components\Md5EmailValidationToken;
use EuroMillions\web\components\NullPasswordHasher;
use EuroMillions\web\entities\User;
use EuroMillions\web\vo\Email;
use EuroMillions\web\vo\Password;
use EuroMillions\web\vo\UserId;
use EuroMillions\web\vo\ValidationToken;
use Money\Currency;
use Money\Money;

class UserBuilder
{
    const DEFAULT_NAME = 'Antonio';
    const DEFAULT_SURNAME = 'Hernández';
    const DEFAULT_EMAIL = 'nonexisting@email.com';
    const DEFAULT_PASSWORD = 'Password01';
    const DEFAULT_COUNTRY = 'Spain';
    const DEFAULT_VALIDATED = 0;
    const DEFAULT_USER_CURRENCY = 'EUR';

    private $name = self::DEFAULT_NAME;
    private $surname = self::DEFAULT_SURNAME;
    private $email;
    private $password;
//    private $rememberToken;
    private $balance;
    private $user_currency;
    private $country = self::DEFAULT_COUNTRY;
    // private $wallet;
    private $validated = self::DEFAULT_VALIDATED;
    private $validationToken;
 //   private $paymentMethod;
    private $street;
    private $zip;
    private $city;
    private $phone_number;
    private $jackpot_reminder;
    private $threshold;
  //  private $playConfig;
 //   private $userNotification;


    public static function aUser()
    {
        return new UserBuilder();
    }

    public function __construct()
    {
        $this->email = new Email(self::DEFAULT_EMAIL);
        $this->password = new Password(self::DEFAULT_PASSWORD, new NullPasswordHasher());
    //    $this->wallet = new Wallet();
        $this->balance = new Money(0, new Currency(self::DEFAULT_USER_CURRENCY));
        $this->validationToken = new ValidationToken($this->email, new Md5EmailValidationToken());
        $this->user_currency = new Currency(self::DEFAULT_USER_CURRENCY);
    }

    public function withPassword(Password $password)
    {
        $this->password = $password;
        return $this;
    }

    public function but()
    {
        return $this;
    }

    public function build()
    {
        $user = new User();
        $user->initialize(get_object_vars($this));
        return $user;
    }
}