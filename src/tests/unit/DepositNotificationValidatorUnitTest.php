<?php


namespace EuroMillions\tests\unit;


use EuroMillions\shared\components\validations_order_notifications\DepositNotificationValidator;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\tests\helpers\builders\OrderBuilder;
use EuroMillions\tests\helpers\mothers\UserMother;
use EuroMillions\web\components\logger\Adapter\CloudWatch;
use EuroMillions\web\entities\DepositTransaction;
use LegalThings\CloudWatchLogger;

class DepositNotificationValidatorUnitTest extends UnitTestBase
{

    protected $order;

    protected $transaction;

    protected $paymentProvider_double;

    protected $orderService_double;

    protected $logger_double;




    public function setUp()
    {
        $this->order = OrderBuilder::anOrder()->buildANewWay();
        $this->paymentProvider_double = $this->getServiceDouble('PaymentProviderService');
        $this->orderService_double = $this->getServiceDouble('OrderService');
        parent::setUp();
    }


    /**
     * method validate
     * when called
     * should returnTrue
     */
    public function test_validate_called_returnTrue()
    {
        $expected = new ActionResult(true);
        $actual = $this->getSut()->validate();
        var_dump(__LINE__, "****************************");
        $this->assertEquals($expected,$actual);
    }


    private function getSut()
    {
        return new DepositNotificationValidator(
                $this->order,
                'PENDING',
                new DepositTransaction($this->getTransaction('SUCCESS')),
                $this->paymentProvider_double->reveal(),
                $this->orderService_double->reveal(),
                $this->logger_double->reveal());
    }




    protected function getTransaction($status)
    {
        $user = UserMother::aUserWith500Eur()->build();
        $now = new \DateTime();
        $wallet_before = $user->getWallet();
        $wallet_after = $user->getWallet();

        return [
            'lottery_id' => 1,
            'numBets' => 3,
            'amountWithWallet' => 2000,
            'amountWithCreditCard' => 0,
            'feeApplied' => 0,
            'user' => $user,
            'walletBefore' => $wallet_before,
            'walletAfter' => $wallet_after,
            'now' => $now,
            'status' => $status,
            'playConfigs' => [1,2],
            'amount' => 0,
            'transactionID' => '123',
            'lotteryName' => 'TEST'
        ];
    }

}