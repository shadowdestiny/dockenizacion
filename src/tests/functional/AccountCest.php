<?php
use EuroMillions\tests\helpers\mothers\UserMother;
/**
 * Class AccountCest
 * @group active
 */
class AccountCest
{
    private $userName;
    const USER_NAME = 'Antonio';

    public function _before(FunctionalTester $I)
    {
        $user = UserMother::anAlreadyRegisteredUser()->withName(self::USER_NAME)->build();
        $this->userName = $user->getName();
        $user_id1 = $user->getId();
        $user_id = $user_id1->id();
        $I->haveInSession('EM_current_user', $user_id);
        $I->persistEntity($user);
        $I->amOnPage('/account');
    }

    public function _after(FunctionalTester $I)
    {
    }

    /**
     * @param FunctionalTester $I
     * @incomplete Fix registration first
     */
    public function seeUserName(FunctionalTester $I)
    {
        $I->canSee('Hello. '.$this->userName);
    }
}
