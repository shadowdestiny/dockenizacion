<?php


namespace tests\unit;

use EuroMillions\entities\User;
use EuroMillions\entities\VisaPaymentMethod;
use EuroMillions\vo\CardHolderName;
use EuroMillions\vo\CardNumber;
use EuroMillions\vo\CreditCard;
use EuroMillions\vo\CVV;
use EuroMillions\vo\ExpiryDate;
use Phalcon\Test\UnitTestCase;
use Prophecy\Argument;

class VisaPaymentMethodUnitTest extends UnitTestCase
{

    /**
     * method charge
     * when called
     * should returnServiceActionResultTrue
     */
    public function test_charge_called_returnServiceActionResultTrue()
    {
        $user = new User();
        $creditCard = new CreditCard(new CardHolderName('Raul Mesa Ros'),
                                     new CardNumber('4444444444444448'),
                                     new ExpiryDate('01/19'),
                                     new CVV('123'));
        $visaPaymentMethod = new VisaPaymentMethod($user, $creditCard);
        $actual = $visaPaymentMethod->charge(12);
        $this->assertTrue($actual->success());
    }

}