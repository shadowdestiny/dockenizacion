<?php
namespace Page;

use FunctionalTester;

class Login
{
    // include url of current page
    public static $URL = '/sign-in';
    public static $emailField = '#email';
    public static $passwordField = '#password';
    public static $loginButton = '#go';

    /** @var  FunctionalTester */
    protected $tester;

    public function __construct(FunctionalTester $I)
    {
        $this->tester = $I;
    }

    public function login($email, $password)
    {

        $I = $this->tester;
        $I->amOnPage(self::$URL);
        $I->fillField(self::$emailField, $email);
        $I->fillField(self::$passwordField, $password);
        $I->click(self::$loginButton);
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
