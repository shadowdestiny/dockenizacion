<?php


namespace EuroMillions\tests\integration;


use EuroMillions\shared\config\Namespaces;
use EuroMillions\web\entities\User;
use EuroMillions\web\services\PrizeCheckoutService;
use Money\Currency;
use Money\Money;
use EuroMillions\tests\base\DatabaseIntegrationTestBase;

class PrizeCheckoutServiceIntegrationTest extends DatabaseIntegrationTestBase
{

    /**
     * Child classes must implement this method. Return empty array if no fixtures are needed
     * @return array
     */
    protected function getFixtures()
    {
        return [
            'users',
            'play_configs',
            'lotteries',
            'euromillions_draws',
        ];
    }

    /**
     * method reChargeAmountAwardedToUser
     * when called
     * should increaseUserBalance
     */
    public function test_reChargeAmountAwardedToUser_called_increaseUserBalance()
    {
        $expected = 306005;
        $userRepository = $this->entityManager->getRepository(Namespaces::ENTITIES_NS.'User');
        $email = 'algarrobo@currojimenez.com';
        /** @var User $user */
        $user = $userRepository->getByEmail($email);
        $amount = new Money(6000, new Currency('EUR'));
        $sut = new PrizeCheckoutService(
            $this->entityManager, $this->getServiceDouble('CurrencyConversionService')->reveal(), $this->getServiceDouble('UserService')->reveal(), $this->getServiceDouble('EmailService')->reveal()
        );
        $sut->reChargeAmountAwardedToUser($user,$amount);
        $this->entityManager->detach($user);
        $user = $userRepository->getByEmail($email);
        $actual = $user->getBalance()->getAmount();
        $this->assertEquals($expected,$actual);
    }


}