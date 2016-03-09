<?php
namespace EuroMillions\tests\unit;
use EuroMillions\web\services\card_payment_providers\FakeCardPaymentProvider;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\vo\CardHolderName;
use EuroMillions\web\vo\CardNumber;
use EuroMillions\web\vo\CreditCard;
use EuroMillions\web\vo\CVV;
use EuroMillions\web\vo\ExpiryDate;
use Money\Currency;
use Money\Money;
use EuroMillions\tests\base\UnitTestBase;

class FakeCardPaymentProviderUnitTest extends UnitTestBase
{
    /**
     * method charge
     * when creditCarNumberIsEven
     * should returnOkOtherwiseKo
     * @dataProvider getCardNumbers
     */
    public function test_charge_creditCarNumberIsEven_returnOkOtherwiseKo($cardNumber, $success)
    {
        $card = new CreditCard(new CardHolderName('azofaifo'), new CardNumber($cardNumber), new ExpiryDate('12/2020'), new CVV('239'));
        $sut = new FakeCardPaymentProvider();
        /** @var ActionResult $actual */
        $actual = $sut->charge(new Money(300, new Currency('EUR')), $card);
        $this->assertEquals($success, $actual->success());
    }

    public function getCardNumbers()
    {
        return [
            ['4929718945750633', false],
            ['4716756280724505', false],
            ['4716646634288838', true],
            ['5277582865517652', true],
            ['5541778778301511', false],
            ['344998273444992', true],
            ['375983465868143', false],
        ];
    }
}