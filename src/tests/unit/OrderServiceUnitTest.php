<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 30/10/18
 * Time: 12:38
 */

namespace EuroMillions\tests\unit;

use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\tests\helpers\mothers\OrderMother;
use EuroMillions\web\entities\DepositTransaction;
use EuroMillions\web\entities\TicketPurchaseTransaction;
use EuroMillions\web\entities\User;
use EuroMillions\web\services\OrderService;
use EuroMillions\web\vo\enum\TransactionType;
use Prophecy\Argument;


class OrderServiceUnitTest extends UnitTestBase
{

    private $walletService_double;

    private $playService_double;

    private $transactionService_double;

    private $logger_double;

    private $playStorageStrategy_double;


    public function setUp()
    {

        $this->walletService_double = $this->getServiceDouble('WalletService');
        $this->playService_double = $this->getServiceDouble('PlayService');
        $this->transactionService_double = $this->getServiceDouble('TransactionService');
        $this->logger_double = $this->prophesize('EuroMillions\web\components\logger\Adapter\CloudWatch');
        $this->playStorageStrategy_double = $this->getInterfaceWebDouble('IPlayStorageStrategy');
        parent::setUp();
    }

    /**
     * method checkout
     * when called
     * should called four times extract method and send email purchase
     */
    public function test_checkout_called_four_times_extract_method_and_send_email_purchase()
    {
        $data = $this->prepareCheckoutMethod();
        $order = $data['order'];
        $transactionID = $data['transactionID'];
        $user = $order->getPlayConfig()[0]->getUser();
        $dataTransaction = [
            'lottery_id' => $order->getLottery()->getId(),
            'transactionID' => $transactionID,
            'numBets' => count($order->getPlayConfig()),
            'feeApplied' => $order->getCreditCardCharge()->getIsChargeFee(),
            'amountWithWallet' => $order->amountForTicketPurchaseTransaction(),
            'walletBefore' => $user->getWallet(),
            'amountWithCreditCard' => 0,
            'playConfigs' => array_map(function ($val) {
                return $val->getId();
            }, $order->getPlayConfig()),
            'discount' => $order->getDiscount()->getValue(),
        ];
        $sut = $this->getSut();
        $this->walletService_double->payOrder($user,$order)->willReturn($user);
        $this->transactionService_double->getTransactionByEmTransactionID($transactionID)->willReturn([$this->transaction($user)]);
        $this->transactionService_double->updateTransaction($this->transaction($user))->shouldBeCalled();
        $this->playService_double->retrieveEuromillionsBundlePrice()->shouldBeCalled();
        $this->playService_double->validatorResult(Argument::any(),Argument::any(),Argument::any(),Argument::any())->willReturn(new ActionResult(true, []));
        $this->playService_double->persistBetDistinctEuroMillions(Argument::any(),Argument::any(),Argument::any(),Argument::any())->willReturn(new ActionResult(true, []));
        $this->walletService_double->extract($user,Argument::any())->shouldBeCalledTimes(4);
        $this->walletService_double->purchaseTransactionGrouped($user,TransactionType::TICKET_PURCHASE,$dataTransaction)->shouldBeCalled();
        $this->playService_double->sendEmailPurchase($user,$order->getPlayConfig())->shouldBeCalled();
        $sut->checkout("","", $data);
    }




    private function prepareCheckoutMethod()
    {
        $order = OrderMother::aJustOrder()->buildANewWay();
        return [
            'order' => $order,
            'transactionID' => '123456789'
        ];
    }

    protected function transaction(User $user)
    {
        $data = [
            'lottery_id' => 1,
            'numBets' => 3,
            'amountWithWallet' => 2000,
            'amountWithCreditCard' => 0,
            'feeApplied' => 0,
            'lotteryName' => 'Euromillions',
            'user' => $user,
            'walletBefore' => $user->getWallet(),
            'walletAfter' => $user->getWallet(),
            'transactionID' => '123456789',
            'amount' => 2000,
            'status' => 'SUCCESS',
            'now' => new \DateTime(),
        ];
        $expected = new DepositTransaction($data);
        $expected->toString();
        return $expected;
    }


    protected function getSut()
    {
        return new OrderService(
            $this->walletService_double->reveal(),
            $this->playService_double->reveal(),
            $this->transactionService_double->reveal(),
            $this->logger_double->reveal(),
            $this->playStorageStrategy_double->reveal()
        );
    }

}