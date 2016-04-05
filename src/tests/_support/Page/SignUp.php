<?php
namespace Page;

use FunctionalTester;

class SignUp
{
    // include url of current page
    public static $URL = '/sign-up';
    public static $nameField = ' #name';
    public static $surnameField = '#surname';
    public static $emailField = '#email-sign-up';
    public static $passwordField = '#password-sign-up';
    public static $confirmPasswordField = '#confirm_password';
    public static $countrySelect = '#country';
    public static $signUpButton = '#goSignUp';

    /** @var  FunctionalTester */
    protected $tester;

    public function __construct(FunctionalTester $I)
    {
        $this->tester = $I;
    }

    public function signUp($name, $surname, $email, $password, $confirm, $country)
    {
        $I = $this->tester;

        $I->amOnPage(self::$URL);
        $I->fillField(self::$nameField, $name);
        $I->fillField(self::$surnameField, $surname);
        $I->fillField(self::$emailField, $email);
        $I->fillField(self::$passwordField, $password);
        $I->fillField(self::$confirmPasswordField, $confirm);
        $I->selectOption(self::$countrySelect, $country);
        $I->click(self::$signUpButton);
        return $this;
    }

    /**
     * Basic route example for your current URL
     * You can append any additional parameter to URL
     * and use it in tests like: Page\Edit::route('/123-post');
     */
    public static function route($param)
    {
        return static::$URL.$param;
    }
}
