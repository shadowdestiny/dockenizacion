<?php


namespace EuroMillions\tests\integration;


use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\tests\base\DatabaseIntegrationTestBase;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\entities\Lottery;
use EuroMillions\web\repositories\UserRepository;
use EuroMillions\web\services\LotteryService;
use EuroMillions\web\vo\Discount;
use Money\Currency;
use Money\Money;
use Prophecy\Argument;

class LotteryServiceIntegrationTest extends DatabaseIntegrationTestBase
{

    protected $lotteryRepository_double;
    protected $lotteriesDataService_double;
    /** @var  UserRepository  */
    protected $userRepository;
    protected $lotteryDrawRepository_double;
    protected $userService_double;
    protected $betService_double;
    protected $emailService_double;
    protected $userNotificationsService_double;
    protected $walletService_double;

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
            'euromillions_draws'
        ];
    }

    public function setUp()
    {
        parent::setUp();
        $this->lotteriesDataService_double = $this->getServiceDouble('LotteriesDataService');
        $this->userService_double = $this->getServiceDouble('UserService');
        $this->lotteryRepository_double = $this->getRepositoryDouble('Lottery');
        $this->userRepository = $this->entityManager->getRepository($this->getEntitiesToArgument('User'));
        $this->lotteryDrawRepository_double = $this->getRepositoryDouble('LotteryDrawRepository');
        $this->betService_double = $this->getServiceDouble('BetService');
        $this->emailService_double = $this->getServiceDouble('EmailService');
        $this->userNotificationsService_double = $this->getServiceDouble('UserNotificationsService');
        $this->walletService_double = $this->getServiceDouble('WalletService');
    }

    /**
     * method placeBetForNextDraw
     * when called
     * should createBet
     */
    public function test_placeBetForNextDraw_called_createBet()
    {
        $date = new \DateTime('2015-09-22');
        $lottery = $this->prepareLotteryEntity('EuroMillions');
        $this->preparePlaceBet($lottery,$date);
        $sut = $this->getSut();
        $sut->placeBetForNextDraw($lottery,$date);
    }

    /**
     * method placeBetForNextDraw
     * when calledWhitoutBalanceToNextPlay
     * should sendEmailNotice
     */
    public function test_placeBetForNextDraw_calledWhitoutBalanceToNextPlay_sendEmailNotice()
    {
        $date = new \DateTime('2015-12-22');
        $lottery = $this->prepareLotteryEntity('EuroMillions');
        $this->preparePlaceBetNoFundsUser($lottery,$date);
        $sut = $this->getSut();
        $sut->placeBetForNextDraw($lottery,$date);
    }


    /**
     * @return LotteryService
     */
    private function getSut()
    {
        $sut = new LotteryService($this->entityManager,
                                  $this->lotteriesDataService_double->reveal(),
                                  $this->userService_double->reveal(),
                                  $this->betService_double->reveal(),
                                  $this->emailService_double->reveal(),
                                  $this->userNotificationsService_double->reveal(),
                                  $this->walletService_double->reveal()
        );

        return $sut;
    }

    private function getUsersWithPlayConfigsOrderByLottery()
    {
        return $this->userRepository->getUsersWithPlayConfigsOrderByLottery();
    }

    /**
     * @param $lottery_name
     * @return Lottery
     */
    protected function prepareLotteryEntity($lottery_name)
    {
        $lottery = new Lottery();
        $lottery->initialize([
            'id'        => 1,
            'name'      => $lottery_name,
            'active'    => 1,
            'frequency' => 'w0100100',
            'draw_time' => '20:00:00',
            'single_bet_price' => new Money(250, new Currency('EUR')),
        ]);
        return $lottery;
    }

    /**
     * @param $lottery
     * @param $date
     */
    private function preparePlaceBet($lottery,$date)
    {
        $users = $this->getUsersWithPlayConfigsOrderByLottery();
        $regular_numbers = [1, 2, 3, 4, 5];
        $lucky_numbers = [5, 8];
        $euroMillionsDraw = new EuroMillionsDraw();
        $euroMillionsDraw->createResult($regular_numbers, $lucky_numbers);
        $playConfigsFilteredUserOne = $users[0]->getPlayConfigsFilteredForNextDraw($lottery->getNextDrawDate($date));
        $playConfigsFilteredUserTwo = $users[1]->getPlayConfigsFilteredForNextDraw($lottery->getNextDrawDate($date));
        $priceUserOne = new Money((int)$lottery->getSingleBetPrice()->getAmount() * count($playConfigsFilteredUserOne), new Currency('EUR'));
        $priceUserTwo = new Money((int)$lottery->getSingleBetPrice()->getAmount() * count($playConfigsFilteredUserTwo), new Currency('EUR'));
        $this->userService_double->getUsersWithPlayConfigsForNextDraw()->willReturn($users);
        $this->lotteriesDataService_double->getPriceForNextDraw($playConfigsFilteredUserOne->toArray())->willReturn($priceUserOne);
        $this->lotteriesDataService_double->getPriceForNextDraw($playConfigsFilteredUserTwo->toArray())->willReturn($priceUserTwo);
        $this->betService_double->validation($playConfigsFilteredUserOne->toArray()[0], Argument::type('EuroMillions\web\entities\EuroMillionsDraw'), Argument::any())->willReturn(new ActionResult(true));
        $this->betService_double->validation($playConfigsFilteredUserTwo->toArray()[0], Argument::type('EuroMillions\web\entities\EuroMillionsDraw'), Argument::any())->willReturn(new ActionResult(true));
        $this->betService_double->validation($playConfigsFilteredUserTwo->toArray()[1], Argument::type('EuroMillions\web\entities\EuroMillionsDraw'), Argument::any())->willReturn(new ActionResult(true));
    }

    private function preparePlaceBetNoFundsUser($lottery,$date)
    {
        $users = $this->getUsersWithPlayConfigsOrderByLottery();
        $regular_numbers = [1, 2, 3, 4, 5];
        $lucky_numbers = [5, 8];
        $euroMillionsDraw = new EuroMillionsDraw();
        $euroMillionsDraw->createResult($regular_numbers, $lucky_numbers);
        $user = $users[2];
        $playConfigsFilteredUserOne = $user->getPlayConfigsFilteredForNextDraw($lottery->getNextDrawDate($date));
        $priceUserOne = new Money((int)$lottery->getSingleBetPrice()->getAmount() * count($playConfigsFilteredUserOne), new Currency('EUR'));
        $this->userService_double->getUsersWithPlayConfigsForNextDraw()->willReturn($users);
        $this->lotteriesDataService_double->getPriceForNextDraw($playConfigsFilteredUserOne->toArray())->willReturn($priceUserOne);
        $this->userNotificationsService_double->hasNotificationActive(Argument::any(),$user)->willReturn(true);
        $this->emailService_double->sendLowBalanceEmail($user)->shouldBeCalled();
    }

}