<?php

use Phalcon\Di;
use EuroMillions\web\vo\dto\PowerBallDrawDTO;
use EuroMillions\megasena\vo\dto\MegaSenaDrawDTO;
use EuroMillions\web\components\EmTranslationAdapter;
use EuroMillions\eurojackpot\vo\dto\EuroJackpotDrawDTO;
use EuroMillions\megamillions\vo\dto\MegaMillionsDrawDTO;
use EuroMillions\tests\helpers\mothers\EuroMillionsDrawMother;
use EuroMillions\shared\components\builder\LotteryDrawDTOBuilder;

class LotteryDrawDTOBuilderCest
{
    /**
     * @var EuroMillions\web\components\EmTranslationAdapter
     */
    protected $emTranslationAdapter;

    public function _before(\UnitTester $I)
    {
        $di = Di::getDefault();
        $entityManager = $di->get('entityManager');
        $this->emTranslationAdapter = new EmTranslationAdapter('en', $entityManager->getRepository('EuroMillions\web\entities\TranslationDetail'));
    }

    /**
     * method build
     * when isPowerBall
     * should returnInstanceOfPowerBallDrawDTO
     * @param UnitTester $I
     * @group dto-builder
     */
    public function test_build_isPowerBall_returnInstanceOfPowerBallDrawDTO(UnitTester $I)
    {
        $aPowerBallDraw = EuroMillionsDrawMother::anPowerBallDrawWithJackpotAndBreakDown()->build();

        $drawsDTO = (new LotteryDrawDTOBuilder())->setEmTranslationAdapter($this->emTranslationAdapter)
            ->setEuromillionsDraw($aPowerBallDraw)
            ->setLotteryName('PowerBall')
            ->build();

        $I->assertTrue($drawsDTO instanceof PowerBallDrawDTO);
    }

    /**
     * method build
     * when isMegaMillions
     * should returnInstanceOfMegaMillionsDrawDTO
     * @param UnitTester $I
     * @group dto-builder
     */
    public function test_build_isMegaMillions_returnInstanceOfMegaMillionsDrawDTO(UnitTester $I)
    {
        $aMegaMillionsDraw = EuroMillionsDrawMother::anotherMegaMillionsDrawWithJackpotAndBreakDown()->build();

        $drawsDTO = (new LotteryDrawDTOBuilder())->setEmTranslationAdapter($this->emTranslationAdapter)
            ->setEuromillionsDraw($aMegaMillionsDraw)
            ->setLotteryName('MegaMillions')
            ->build();

        $I->assertTrue($drawsDTO instanceof MegaMillionsDrawDTO);
    }

    /**
     * method build
     * when isEuroJackpot
     * should returnInstanceOfEuroJackpotDrawDTO
     * @param UnitTester $I
     * @group dto-builder
     */
    public function test_build_isEuroJackpot_returnInstanceOfEuroJackpotDrawDTO(UnitTester $I)
    {
        $aEuroJackpotDraw = EuroMillionsDrawMother::anEuroJackpotDrawWithJackpotAndBreakDown()->build();

        $drawsDTO = (new LotteryDrawDTOBuilder())->setEmTranslationAdapter($this->emTranslationAdapter)
            ->setEuromillionsDraw($aEuroJackpotDraw)
            ->setLotteryName('EuroJackpot')
            ->build();

        $I->assertTrue($drawsDTO instanceof EuroJackpotDrawDTO);
    }

    /**
     * method build
     * when isMegaSena
     * should returnInstanceOfMegaSenaDrawDTO
     * @param UnitTester $I
     * @group dto-builder
     */
    public function test_build_isMegaSena_returnInstanceOfMegaSenaDrawDTO(UnitTester $I)
    {
        $aMegaSenaDraw = EuroMillionsDrawMother::aMegaSenaDrawWithJackpotAndBreakDown()->build();

        $drawsDTO = (new LotteryDrawDTOBuilder())->setEmTranslationAdapter($this->emTranslationAdapter)
            ->setEuromillionsDraw($aMegaSenaDraw)
            ->setLotteryName('MegaSena')
            ->build();

        $I->assertTrue($drawsDTO instanceof MegaSenaDrawDTO);
    }

}
