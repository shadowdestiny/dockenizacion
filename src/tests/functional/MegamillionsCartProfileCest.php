<?php
/**
 * Created by PhpStorm.
 * User: wilcar
 * Date: 17/01/19
 * Time: 02:32 PM
 */

class MegamillionsCartProfileCest
{
    public function _before(FunctionalTester $I)
    {
        $I->amOnPage('/megamillions/cart/profile');
    }

    /**
     * @group megamillions
     * @param FunctionalTester $I
     */
    public function seeSigUpForm(FunctionalTester $I)
    {
        $domainServiceFactory= \Phalcon\Di::getDefault()->get('domainServiceFactory');
        $languageService= $domainServiceFactory->getLanguageService();
        $I->seeInTitle('Log In or Sign Up');
        $I->see($languageService->translate("signup_LogIn_btn"));
        $I->see($languageService->translate("signin_signup_btn"));
        $I->see('Date of Birth');
        $I->see('Country of Residence');
        $I->see('Phone number');
    }
}
