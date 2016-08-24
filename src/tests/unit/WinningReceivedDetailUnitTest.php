<?php


namespace tests\unit;


use EuroMillions\shared\config\Namespaces;
use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\tests\helpers\mothers\UserMother;
use EuroMillions\web\components\transaction\detail\WinningReceiveDetail;
use EuroMillions\web\entities\Bet;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\entities\Lottery;
use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\entities\WinningsReceivedTransaction;
use EuroMillions\web\vo\EuroMillionsLine;
use EuroMillions\web\vo\EuroMillionsLuckyNumber;
use EuroMillions\web\vo\EuroMillionsRegularNumber;
use Money\Currency;
use Money\Money;
use Prophecy\Argument;

class WinningReceivedDetailUnitTest extends UnitTestBase
{
    private $betRepository_double;
    private $lotteryDrawRepository_double;
    private $lotteryRepository_double;

    protected function getEntityManagerStubExtraMappings()
    {
        return [
            Namespaces::ENTITIES_NS . 'Bet'       => $this->betRepository_double,
            Namespaces::ENTITIES_NS . 'EuroMillionsDraw'       => $this->lotteryDrawRepository_double,
            Namespaces::ENTITIES_NS . 'Lottery'       => $this->lotteryRepository_double,
        ];
    }

    public function setUp()
    {
        $this->betRepository_double = $this->getRepositoryDouble('BetRepository');
        $this->lotteryDrawRepository_double = $this->getRepositoryDouble('LotteryDrawRepository');
        $this->lotteryRepository_double = $this->getRepositoryDouble('LotteryRepository');
        parent::setUp();
    }

    /**
     * method obtainDataForDetails
     * when called
     * should returnAProperDTOWithData
     */
    public function test_obtainDataForDetails_called_returnAProperDTOWithData()
    {
        $user = UserMother::aUserWith500Eur()->build();
        $now = new \DateTime();
        $wallet_before = $user->getWallet();
        $wallet_after = $user->getWallet();

        $lottery = new Lottery();
        $lottery->initialize([
            'id'        => 1,
            'name'      => 'Euromillions',
            'active'    => 1,
            'frequency' => 'w0100100',
            'draw_time' => '20:00:00'
        ]);

        $data = [
            'lottery_id' => 1,
            'draw_id' => 1,
            'bet_id' => 3,
            'amount' => '10000',
            'state' => '',
            'walletBefore' => $wallet_before,
            'walletAfter' => $wallet_after,
            'now' => $now,
            'user' => $user,
        ];
        list($playConfig,$euroMillionsDraw) = $this->getPlayConfigAndEuroMillionsDraw();
        $bet = [new Bet($playConfig,$euroMillionsDraw)];

        $transaction = new WinningsReceivedTransaction($data);
        $transaction->toString();
        $this->betRepository_double->obtainWinnerBetById(3)->willReturn([$bet]);
        $this->lotteryDrawRepository_double->find(['id'=> 1])->willReturn($euroMillionsDraw);
        $sut = new WinningReceiveDetail($this->getEntityManagerRevealed(), $transaction);
        $actual = $sut->obtainDataForDetails();
        $this->assertCount(1,$actual);
        $this->assertInstanceOf('EuroMillions\web\vo\dto\WinningReceiveDetailDTO',$actual[0]);
    }


    private function getPlayConfigAndEuroMillionsDraw()
    {
        $user = UserMother::aUserWith500Eur()->build();
        $regular_numbers = [1, 2, 3, 4, 5];
        $lucky_numbers = [5, 8];
        $euroMillionsDraw = new EuroMillionsDraw();
        $euroMillionsLine = new EuroMillionsLine($this->getRegularNumbers($regular_numbers),
            $this->getLuckyNumbers($lucky_numbers));
        $euroMillionsDraw->createResult($regular_numbers, $lucky_numbers);
        $lottery = new Lottery();
        $lottery->initialize([
            'id'        => 1,
            'name'      => 'EuroMillions',
            'active'    => 1,
            'frequency' => 'freq',
            'draw_time' => 'draw',
            'single_bet_price' => new Money(23500, new Currency('EUR')),
        ]);
        $euroMillionsDraw->setLottery($lottery);
        $playConfig = new PlayConfig();
        $playConfig->initialize([
                'user' => $user,
                'line' => $euroMillionsLine,
                'startDrawDate' => new \DateTime()
            ]
        );
        return [$playConfig,$euroMillionsDraw];
    }

    protected function getRegularNumbers(array $numbers)
    {
        $result = [];
        foreach ($numbers as $number) {
            $result[] = new EuroMillionsRegularNumber($number);
        }
        return $result;
    }
    protected function getLuckyNumbers(array $numbers)
    {
        $result = [];
        foreach ($numbers as $number) {
            $result[] = new EuroMillionsLuckyNumber($number);
        }
        return $result;
    }



}