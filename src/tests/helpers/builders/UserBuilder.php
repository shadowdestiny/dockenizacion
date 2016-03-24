<?php
namespace EuroMillions\tests\helpers\builders;

use EuroMillions\shared\vo\Wallet;
use EuroMillions\web\components\Md5EmailValidationToken;
use EuroMillions\web\components\NullPasswordHasher;
use EuroMillions\web\entities\User;
use EuroMillions\web\vo\Email;
use EuroMillions\web\vo\Password;
use EuroMillions\web\vo\ValidationToken;
use Money\Currency;

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
    private $user_currency;
    private $country = self::DEFAULT_COUNTRY;
    private $wallet;
    private $validated = self::DEFAULT_VALIDATED;
    private $validationToken;
    private $street;
    private $zip;
    private $city;
    private $phone_number;
    private $jackpot_reminder;


    public static function aUser()
    {
        return new UserBuilder();
    }

    public function __construct()
    {
        $this->email = new Email(self::DEFAULT_EMAIL);
        $this->password = new Password(self::DEFAULT_PASSWORD, new NullPasswordHasher());
        $this->wallet = new Wallet();
        $this->validationToken = new ValidationToken($this->email, new Md5EmailValidationToken());
        $this->user_currency = new Currency(self::DEFAULT_USER_CURRENCY);
    }

    /**
     * @param string $id
     * @return UserBuilder
     */
    public function withId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param Password $password
     * @return UserBuilder
     */
    public function withPassword(Password $password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @param Wallet $wallet
     * @return UserBuilder
     */
    public function withWallet(Wallet $wallet)
    {
        $this->wallet = $wallet;
        return $this;
    }

    public function withName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return UserBuilder
     */
    public function but()
    {
        return $this;
    }

    /**
     * @return User
     */
    public function build()
    {
        $user = new User();
        $user->initialize(get_object_vars($this));
        return $user;
    }
}