<?php

namespace EuroMillions\tests\unit;

use EuroMillions\shared\components\transactionBuilders\WinningTransactionDataBuilder;
use EuroMillions\shared\vo\Winning;
use EuroMillions\tests\base\EuroMillionsResultRelatedTest;
use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\web\vo\enum\TransactionType;
use Money\Currency;
use Money\Money;

class WinningTransactionDataBuilderUnitTest extends UnitTestBase
{
    use EuroMillionsResultRelatedTest;

    private $betEntity_double;
    private $userEntity_double;

    public function setUp()
    {
        $this->betEntity_double = $this->getEntityDouble('Bet');
        $this->userEntity_double = $this->getEntityDouble('User');

        parent::setUp();
    }

    public function test_generateWinningTransactionsGreaterThanOrEqualThreshold_called_returnGetData()
    {
        $sut = $this->getSut(10000, 1000);

        $sut->generate();

        $actual = $sut->getData();

        $expected = array(
            'draw_id' => 1,
            'bet_id' => 1,
            'amount' => 10000,
            'user' => $this->userEntity_double->reveal(),
            'walletBefore' => null,
            'walletAfter' => null,
            'state' => 'pending',
            'now' => new \DateTime()
        );

        $this->assertEquals(TransactionType::BIG_WINNING, $sut->getType());
        $this->assertTrue($sut->greaterThanOrEqualThreshold());
        $this->assertEquals($expected, $actual);
    }

    public function test_generateWinningTransactionsNotGreaterThanOrEqualThreshold_called_returnGetData()
    {
        $sut = $this->getSut(10000, 100000);

        $sut->generate();

        $actual = $sut->getData();

        $expected = array(
            'draw_id' => 1,
            'bet_id' => 1,
            'amount' => 10000,
            'user' => $this->userEntity_double->reveal(),
            'walletBefore' => null,
            'walletAfter' => null,
            'state' => '',
            'lottery_id' => 1,
            'now' => new \DateTime()
        );

        $this->assertEquals(TransactionType::WINNINGS_RECEIVED, $sut->getType());
        $this->assertFalse($sut->greaterThanOrEqualThreshold());
        $this->assertEquals($expected, $actual);
    }

    private function getSut($amount, $threshold){
        $euroMillionsDraw_stub = $this->getEntityDouble('EuroMillionsDraw');
        $euroMillionsDraw_stub->getId()->willReturn(1);

        $this->betEntity_double->getEuroMillionsDraw()->willReturn($euroMillionsDraw_stub->reveal());
        $this->betEntity_double->getId()->willReturn(1);

        $winning = new Winning(new Money((int) $amount , new Currency('EUR')), new Money((int) $threshold , new Currency('EUR')), 1);
        $amount = new Money((int) $amount , new Currency('EUR'));

        return new WinningTransactionDataBuilder(
            $winning,
            $this->betEntity_double->reveal(),
            $this->userEntity_double->reveal(),
            $amount
        );
    }
}