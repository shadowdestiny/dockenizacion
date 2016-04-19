<?php
namespace EuroMillions\tests\helpers\mothers;

use EuroMillions\shared\vo\Wallet;
use EuroMillions\web\components\NullPasswordHasher;
use EuroMillions\web\components\PhpassWrapper;
use EuroMillions\web\interfaces\IPasswordHasher;
use EuroMillions\web\vo\Password;
use Money\Currency;
use Money\Money;
use EuroMillions\tests\helpers\builders\UserBuilder;

class UserMother
{
    /**
     * @param IPasswordHasher $hasher
     * @return UserBuilder
     */
    public static function aJustRegisteredUser(IPasswordHasher $hasher = null)
    {
        return self::getInitializedUser($hasher);
    }

    public static function anAlreadyRegisteredUser()
    {
        return self::aUserWithNoMoney();
    }
    
    public static function aRegisteredUserWithEncryptedPassword()
    {
        return self::getInitializedUser(new PhpassWrapper())
            ->withId(UserBuilder::DEFAULT_ID)
            ->withName(UserBuilder::DEFAULT_NAME)
            ->withEmail(UserBuilder::DEFAULT_EMAIL);
    }

    public static function aUserWith50Eur()
    {
        return self::getInitializedUser()
            ->withId(UserBuilder::DEFAULT_ID)
            ->withWallet(self::getWallet(5000));
    }

    public static function aUserWith500Eur()
    {
        return self::getInitializedUser()
            ->withId(UserBuilder::DEFAULT_ID)
            ->withWallet(self::getWallet(50000));
    }

    public static function aUserWith40EurWinnings()
    {
        return self::getInitializedUser()
            ->withId(UserBuilder::DEFAULT_ID)
            ->withWallet(self::getWallet(3000));
    }

    public static function aUserWithNoMoney()
    {
        return self::getInitializedUser()
            ->withId(UserBuilder::DEFAULT_ID);
    }

    /**
     * @param IPasswordHasher $hasher
     * @return UserBuilder
     */
    private static function getInitializedUser(IPasswordHasher $hasher = null)
    {
        if (!$hasher) {
            $hasher = new NullPasswordHasher();
        }
        $user_builder = UserBuilder::aUser()
            ->withPassword(new Password(UserBuilder::DEFAULT_PASSWORD, $hasher));
        return $user_builder;
    }

    /**
     * @param $amount
     * @return Wallet
     */
    private static function getWallet($amount)
    {
        return new Wallet(new Money($amount, new Currency('EUR')));
    }

}