<?php
use EuroMillions\tests\helpers\mothers\UserMother;
/**
 * Class AccountCest
 */
class AccountCest
{
    private $userName;
    const USER_NAME = 'Antonio';

    public function _before(FunctionalTester $I)
    {
        $user = UserMother::anAlreadyRegisteredUser()->withName(self::USER_NAME)->build();
        $this->userName = $user->getName();
        $user_id = $user->getId();
        $I->haveInDatabase('users', $user->toArray());
        $I->haveInSession('EM_current_user', $user_id);
        $I->amOnPage('/account');
    }

    public function _after(FunctionalTester $I)
    {
    }

    /**
     * @param FunctionalTester $I
     */
    public function seeUserName(FunctionalTester $I)
    {
        $I->canSee('Hello. '.$this->userName);
    }
}
