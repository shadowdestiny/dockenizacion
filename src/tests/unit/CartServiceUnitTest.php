<?php


namespace EuroMillions\tests\unit;


use EuroMillions\shared\config\Namespaces;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\tests\helpers\mothers\LotteryMother;
use EuroMillions\tests\helpers\mothers\OrderMother;
use EuroMillions\tests\helpers\mothers\UserMother;
use EuroMillions\web\services\CartService;
use Money\Currency;
use Money\Money;

class CartServiceUnitTest extends UnitTestBase
{


    private $userRepository_double;
    private $orderStorageStrategy_double;
    private $lotteryRepository_double;
    private $siteConfigService_double;

    protected function getEntityManagerStubExtraMappings()
    {
        return [
            Namespaces::ENTITIES_NS . 'User' => $this->userRepository_double,
            Namespaces::ENTITIES_NS . 'Lottery' => $this->lotteryRepository_double,
        ];
    }
    public function setUp()
    {
        $this->userRepository_double = $this->getRepositoryDouble('UserRepository');
        $this->lotteryRepository_double = $this->getRepositoryDouble('LotteryRepository');
        $this->orderStorageStrategy_double = $this->getInterfaceWebDouble('IPlayStorageStrategy');
        $this->siteConfigService_double = $this->getSharedServiceDouble('SiteConfigService');
        parent::setUp(); // TODO: Change the autogenerated stub
    }



    /**
     * method saveOrderToStorage
     * when called
     * should returnActionResultTrue
     */
    public function test_saveOrderToStorage_called_returnActionResultTrue()
    {
        $expected = new ActionResult(true);
        $order = OrderMother::aJustOrder()->build();
        $user_id = $order->getPlayConfig()[0]->getUser()->getId();
        $this->orderStorageStrategy_double->save($order->toJsonData(), $user_id)->willReturn(new ActionResult(true));
        $sut = $this->getSut();
        $actual = $sut->store($order);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method getOrderFromStorage
     * when calledPassingAKeyValid
     * should returnActionResultTrueWithOrder
     */
    public function test_getOrderFromStorage_calledPassingAKeyValid_returnActionResultTrueWithOrder()
    {
        $order = OrderMother::aJustOrder()->build();
        $expected = new ActionResult(true, $order);
        $user = UserMother::aUserWith50Eur()->build();
        $lottery = LotteryMother::anEuroMillions();
        $user_id = $user->getId();
        $this->orderStorageStrategy_double->findByKey($user_id)->willReturn(new ActionResult(true,$order->toJsonData()));
        $this->lotteryRepository_double->findOneBy(['name' => 'EuroMillions'])->willReturn($lottery);
        $this->userRepository_double->find(['id' => $user_id])->willReturn($user);
        $this->siteConfigService_double->getFee()->willReturn(new Money(35, new Currency('EUR')));
        $this->siteConfigService_double->getFeeToLimitValue()->willReturn(new Money(1200, new Currency('EUR')));
        $sut = $this->getSut();
        $actual = $sut->get($user_id);
        $this->assertEquals($expected, $actual);
    }


    /**
     * method getOrderFromStorage
     * when calledWithAKeyValidButOrderNoExist
     * should returnActionResultFalseWithErrorMessage
     */
    public function test_getOrderFromStorage_calledWithAKeyValidButOrderNoExist_returnActionResultFalseWithErrorMessage()
    {
        $expected = new ActionResult(false, 'Order doesn\'t exist');
        $user = UserMother::aUserWith50Eur()->build();
        $user_id = $user->getId();
        $lottery = LotteryMother::anEuroMillions();
        $this->orderStorageStrategy_double->findByKey($user_id)->willReturn(new ActionResult(false));
        $this->lotteryRepository_double->findOneBy(['name' => 'EuroMillions'])->willReturn($lottery);
        $this->userRepository_double->find(['id' => $user_id])->willReturn($user);
        $sut = $this->getSut();
        $actual = $sut->get($user_id);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method getOrderFromStorage
     * when calledWithAKEyValidButJsonIsMalFormed
     * should returnActionResultFalse
     */
    public function test_getOrderFromStorage_calledWithAKEyValidButJsonIsMalFormed_returnActionResultFalse()
    {
        $expected = new ActionResult(false);
        $lottery = LotteryMother::anEuroMillions();
        $user = UserMother::aUserWith50Eur()->build();
        $user_id = $user->getId();
        $this->orderStorageStrategy_double->findByKey($user_id)->willReturn(new ActionResult(true,NULL));
        $this->lotteryRepository_double->findOneBy(['name' => 'EuroMillions'])->willReturn($lottery);
        $this->userRepository_double->find(['id' => $user_id])->willReturn($user);
        $sut = $this->getSut();
        $actual = $sut->get($user_id);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method get
     * when called
     * should returnOrderWithFees
     */
    public function test_get_called_returnOrderWithFees()
    {
        $order = OrderMother::aJustOrder()->build();
        $lottery = LotteryMother::anEuroMillions();
        $user = UserMother::aUserWith50Eur()->build();
        $user_id = $user->getId();
        $this->orderStorageStrategy_double->findByKey($user_id)->willReturn(new ActionResult(true,$order->toJsonData()));
        $this->lotteryRepository_double->findOneBy(['name' => 'EuroMillions'])->willReturn($lottery);
        $this->userRepository_double->find(['id' => $user_id])->willReturn($user);
        $this->siteConfigService_double->getFee()->willReturn(new Money(35, new Currency('EUR')));
        $this->siteConfigService_double->getFeeToLimitValue()->willReturn(new Money(1200, new Currency('EUR')));
        $sut = $this->getSut();
        $actual = $sut->get($user_id);
        $this->assertEquals($order->getTotal(), $actual->getValues()->getTotal());
        $this->assertEquals($order->getFee(), $actual->getValues()->getFee());
    }


    private function getSut()
    {
        return new CartService(
            $this->getEntityManagerRevealed(),
            $this->orderStorageStrategy_double->reveal(),
            $this->siteConfigService_double->reveal()
        );
    }



}