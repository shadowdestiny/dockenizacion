<?php
namespace EuroMillions\web\services;

use Doctrine\ORM\EntityManager;
use EuroMillions\web\entities\Lottery;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\exceptions\DataMissingException;
use EuroMillions\web\exceptions\ValidDateRangeException;
use EuroMillions\web\repositories\LotteryDrawRepository;
use EuroMillions\web\repositories\LotteryRepository;
use EuroMillions\web\services\external_apis\LotteryApisFactory;
use EuroMillions\web\vo\EuroMillionsDrawBreakDown;
use EuroMillions\web\vo\EuroMillionsJackpot;
use Money\Currency;
use Money\Money;

class LotteriesDataService
{
    /** @var  LotteryDrawRepository */
    protected $lotteryDrawRepository;
    /** @var  LotteryRepository */
    protected $lotteryRepository;
    protected $apisFactory;
    protected $entityManager;

    public function __construct(EntityManager $entityManager, LotteryApisFactory $apisFactory)
    {
        $this->entityManager = $entityManager;
        $this->lotteryDrawRepository = $this->entityManager->getRepository('EuroMillions\web\entities\EuroMillionsDraw');
        $this->lotteryRepository = $this->entityManager->getRepository('EuroMillions\web\entities\Lottery');
        $this->apisFactory = $apisFactory;
    }

    public function getRaffle($lotteryName, \DateTime $now = null)
    {
        if (!$now) {
            $now = new \DateTime();
        }
        try {
            /** @var Lottery $lottery */
            $lottery = $this->lotteryRepository->findOneBy(['name' => $lotteryName]);
            $result_api = $this->apisFactory->resultApi($lottery);
            $last_draw_date = $lottery->getLastDrawDate($now);
            $result = $result_api->getRaffleForDate($lotteryName, $last_draw_date->format('Y-m-d'));
            if (empty($result)) {
                $result = $result_api->getRaffleForDateSecond($lotteryName, $last_draw_date->format('Y-m-d')); //ok
            }
        } catch (\Exception $e) {
            throw new \Exception('Error getting results');
        }
    }

    public function updateNextDrawJackpot($lotteryName, \DateTime $now = null)
    {
        if (!$now) {
            $now = new \DateTime();
        }
        /** @var Lottery $lottery */
        $lottery = $this->lotteryRepository->findOneBy(['name' => $lotteryName]);
        $next_draw_date = $lottery->getNextDrawDate($now);
        try {
            $jackpot_api = $this->apisFactory->jackpotApi($lottery);
            $jackpot = $jackpot_api->getJackpotForDate($lotteryName, $next_draw_date->format("Y-m-d"));
            if (empty($jackpot)) {
                $jackpot = $jackpot_api->getJackpotForDateSecond($lotteryName, $next_draw_date->format("Y-m-d")); //ok
            }
            /** @var EuroMillionsDraw $draw */
            $draw = $this->lotteryDrawRepository->findOneBy(['lottery' => $lottery, 'draw_date' => $next_draw_date]);
            if (!$draw) {
                $draw = $this->createDraw($next_draw_date, $jackpot, $lottery);
            } else {
                $draw->setJackpot($jackpot);
            }
            $this->entityManager->persist($draw);
            $this->entityManager->flush();
        } catch ( ValidDateRangeException $e ) {
            $jackpot = EuroMillionsJackpot::fromAmountIncludingDecimals(1500000000);
            $draw = $this->lotteryDrawRepository->findOneBy(['lottery' => $lottery, 'draw_date' => $next_draw_date]);
            if (!$draw) {
                $draw = $this->createDraw($next_draw_date, $jackpot, $lottery);
            } else {
                $draw->setJackpot($jackpot);
            }
            $this->entityManager->persist($draw);
            $this->entityManager->flush();
            //throw new DataMissingException();
            return $jackpot;
        }
        return $jackpot;
    }

    public function updateLastDrawResult($lotteryName, \DateTime $now = null)
    {
        if (!$now) {
            $now = new \DateTime();
        }

        $last_draw_result = $this->getLastDrawResult($lotteryName, $now);
        if ($last_draw_result==NULL) {
            try {
                /** @var Lottery $lottery */
                $lottery = $this->lotteryRepository->findOneBy(['name' => $lotteryName]);
                $result_api = $this->apisFactory->resultApi($lottery);
                $last_draw_date = $lottery->getLastDrawDate($now);

                $result = $result_api->getResultForDate($lotteryName, $last_draw_date->format('Y-m-d'));
                if (empty($result)) {
                    $result = $result_api->getResultForDateSecond($lotteryName, $last_draw_date->format('Y-m-d')); //ok
                }
                try {
                    /** @var EuroMillionsDraw $draw */
                    $draw = $this->lotteryDrawRepository->getLastDraw($lottery);
                } catch (DataMissingException $e) {
                    $draw = $this->createDraw($last_draw_date, null, $lottery);
                }
                $draw->createResult($result['regular_numbers'], $result['lucky_numbers']);
                $this->entityManager->persist($draw);
                $this->entityManager->flush();
                return $draw->getResult();
            } catch (\Exception $e) {
                throw new \Exception('Error updating results');
            }
        }

    }

    /**
     * @param $lotteryName
     * @param \DateTime|null $now
     * @return EuroMillionsDraw
     * @throws \Exception
     */
    public function updateLastBreakDown($lotteryName, \DateTime $now = null)
    {
        if (!$now) {
            $now = new \DateTime();
        }
        try {
            /** @var Lottery $lottery */
            $lottery = $this->lotteryRepository->findOneBy(['name' => $lotteryName]);
            $result_api = $this->apisFactory->resultApi($lottery);
            $last_draw_date = $lottery->getLastDrawDate($now);
            $result = $result_api->getResultBreakDownForDate($lotteryName, $last_draw_date->format('Y-m-d'));
            if (empty($result)) {
                $result = $result_api->getResultBreakDownForDateSecond($lotteryName, $last_draw_date->format('Y-m-d')); //ok
            }
            /** @var EuroMillionsDraw $draw */
            $draw = $this->lotteryDrawRepository->findOneBy(['lottery' => $lottery, 'draw_date' => $last_draw_date]);
            $draw->createBreakDown($result);
            $this->entityManager->flush();
            return $draw;
        } catch (\Exception $e) {
            throw new \Exception('Error updating results');
        }
    }


    /**
     * @param array $playConfigs
     * @return Money
     */
    public function getPriceForNextDraw(array $playConfigs)
    {
        $price = new Money(0, new Currency('EUR'));
        /** @var PlayConfig $playConfig */
        foreach ($playConfigs as $playConfig) {
            $price = $price->add($playConfig->getSinglePrice());
        }
        return $price;
    }

    /**
     * @param $next_draw_date
     * @param $jackpot
     * @param $lottery
     * @return EuroMillionsDraw
     */
    protected function createDraw($next_draw_date, $jackpot, $lottery)
    {
        $draw = new EuroMillionsDraw();
        $draw->initialize([
            'draw_date' => $next_draw_date,
            'jackpot'   => $jackpot,
            'lottery'   => $lottery
        ]);
        return $draw;
    }

    /**
     * @param $lotteryName
     * @param \DateTime|null $now
     * @return EuroMillionsDraw
     */
    public function getLastDrawResult($lotteryName, \DateTime $now = null)
    {
        $result = NULL;

        $lottery = $this->lotteryRepository->findOneBy(['name' => $lotteryName]);

        $draw = $this->lotteryDrawRepository->getLastDraw($lottery);
        $last_draw = $draw->getDrawDate()->format('j M Y');

        $regular_number_0 = $draw->getResult()->getRegularNumbersArray()[0];
        $regular_number_1 = $draw->getResult()->getRegularNumbersArray()[1];
        $regular_number_2 = $draw->getResult()->getRegularNumbersArray()[2];
        $regular_number_3 = $draw->getResult()->getRegularNumbersArray()[3];
        $regular_number_4 = $draw->getResult()->getRegularNumbersArray()[4];

        $lucky_number_0 = $draw->getResult()->getLuckyNumbersArray()[0];
        $lucky_number_1 = $draw->getResult()->getLuckyNumbersArray()[1];

        $category_one_name = $draw->getBreakDown()->getCategoryOne()->getName();
        $category_one_amount = $draw->getBreakDown()->getCategoryOne()->getLotteryPrize()->getAmount();
        $category_one_currency_name = $draw->getBreakDown()->getCategoryOne()->getLotteryPrize()->getCurrency()->getName();
        $category_one_winners = $draw->getBreakDown()->getCategoryOne()->getWinners();

        $category_two_name = $draw->getBreakDown()->getCategoryTwo()->getName();
        $category_two_amount = $draw->getBreakDown()->getCategoryTwo()->getLotteryPrize()->getAmount();
        $category_two_currency_name = $draw->getBreakDown()->getCategoryTwo()->getLotteryPrize()->getCurrency()->getName();
        $category_two_winners = $draw->getBreakDown()->getCategoryTwo()->getWinners();

        $category_three_name = $draw->getBreakDown()->getCategoryThree()->getName();
        $category_three_amount = $draw->getBreakDown()->getCategoryThree()->getLotteryPrize()->getAmount();
        $category_three_currency_name = $draw->getBreakDown()->getCategoryThree()->getLotteryPrize()->getCurrency()->getName();
        $category_three_winners = $draw->getBreakDown()->getCategoryThree()->getWinners();

        $category_four_name = $draw->getBreakDown()->getCategoryFour()->getName();
        $category_four_amount = $draw->getBreakDown()->getCategoryFour()->getLotteryPrize()->getAmount();
        $category_four_currency_name = $draw->getBreakDown()->getCategoryFour()->getLotteryPrize()->getCurrency()->getName();
        $category_four_winners = $draw->getBreakDown()->getCategoryFour()->getWinners();

        $category_five_name = $draw->getBreakDown()->getCategoryFive()->getName();
        $category_five_amount = $draw->getBreakDown()->getCategoryFive()->getLotteryPrize()->getAmount();
        $category_five_currency_name = $draw->getBreakDown()->getCategoryFive()->getLotteryPrize()->getCurrency()->getName();
        $category_five_winners = $draw->getBreakDown()->getCategoryFive()->getWinners();

        $category_six_name = $draw->getBreakDown()->getCategorySix()->getName();
        $category_six_amount = $draw->getBreakDown()->getCategorySix()->getLotteryPrize()->getAmount();
        $category_six_currency_name = $draw->getBreakDown()->getCategorySix()->getLotteryPrize()->getCurrency()->getName();
        $category_six_winners = $draw->getBreakDown()->getCategorySix()->getWinners();

        $category_seven_name = $draw->getBreakDown()->getCategorySeven()->getName();
        $category_seven_amount = $draw->getBreakDown()->getCategorySeven()->getLotteryPrize()->getAmount();
        $category_seven_currency_name = $draw->getBreakDown()->getCategorySeven()->getLotteryPrize()->getCurrency()->getName();
        $category_seven_winners = $draw->getBreakDown()->getCategorySeven()->getWinners();

        $category_eight_name = $draw->getBreakDown()->getCategoryEight()->getName();
        $category_eight_amount = $draw->getBreakDown()->getCategoryEight()->getLotteryPrize()->getAmount();
        $category_eight_currency_name = $draw->getBreakDown()->getCategoryEight()->getLotteryPrize()->getCurrency()->getName();
        $category_eight_winners = $draw->getBreakDown()->getCategoryEight()->getWinners();

        $category_nine_name = $draw->getBreakDown()->getCategoryNine()->getName();
        $category_nine_amount = $draw->getBreakDown()->getCategoryNine()->getLotteryPrize()->getAmount();
        $category_nine_currency_name = $draw->getBreakDown()->getCategoryNine()->getLotteryPrize()->getCurrency()->getName();
        $category_nine_winners = $draw->getBreakDown()->getCategoryNine()->getWinners();

        $category_ten_name = $draw->getBreakDown()->getCategoryTen()->getName();
        $category_ten_amount = $draw->getBreakDown()->getCategoryTen()->getLotteryPrize()->getAmount();
        $category_ten_currency_name = $draw->getBreakDown()->getCategoryTen()->getLotteryPrize()->getCurrency()->getName();
        $category_ten_winners = $draw->getBreakDown()->getCategoryTen()->getWinners();

        $category_eleven_name = $draw->getBreakDown()->getCategoryEleven()->getName();
        $category_eleven_amount = $draw->getBreakDown()->getCategoryEleven()->getLotteryPrize()->getAmount();
        $category_eleven_currency_name = $draw->getBreakDown()->getCategoryEleven()->getLotteryPrize()->getCurrency()->getName();
        $category_eleven_winners = $draw->getBreakDown()->getCategoryEleven()->getWinners();

        $category_twelve_name = $draw->getBreakDown()->getCategoryTwelve()->getName();
        $category_twelve_amount = $draw->getBreakDown()->getCategoryTwelve()->getLotteryPrize()->getAmount();
        $category_twelve_currency_name = $draw->getBreakDown()->getCategoryTwelve()->getLotteryPrize()->getCurrency()->getName();
        $category_twelve_winners = $draw->getBreakDown()->getCategoryTwelve()->getWinners();

        $category_thirteen_name = $draw->getBreakDown()->getCategoryThirteen()->getName();
        $category_thirteen_amount = $draw->getBreakDown()->getCategoryThirteen()->getLotteryPrize()->getAmount();
        $category_thirteen_currency_name = $draw->getBreakDown()->getCategoryThirteen()->getLotteryPrize()->getCurrency()->getName();
        $category_thirteen_winners = $draw->getBreakDown()->getCategoryThirteen()->getWinners();

        if (isset($last_draw))
        {
            if (
                isset($regular_number_0) &&
                isset($regular_number_1) &&
                isset($regular_number_2) &&
                isset($regular_number_3) &&
                isset($regular_number_4) &&
                isset($lucky_number_0) &&
                isset($lucky_number_1) &&
                isset($category_one_name) &&
                isset($category_one_amount) &&
                isset($category_one_currency_name) &&
                isset($category_one_winners) &&
                isset($category_two_name) &&
                isset($category_two_amount) &&
                isset($category_two_currency_name) &&
                isset($category_two_winners) &&
                isset($category_three_name) &&
                isset($category_three_amount) &&
                isset($category_three_currency_name) &&
                isset($category_three_winners) &&
                isset($category_four_name) &&
                isset($category_four_amount) &&
                isset($category_four_currency_name) &&
                isset($category_four_winners) &&
                isset($category_five_name) &&
                isset($category_five_amount) &&
                isset($category_five_currency_name) &&
                isset($category_five_winners) &&
                isset($category_six_name) &&
                isset($category_six_amount) &&
                isset($category_six_currency_name) &&
                isset($category_six_winners) &&
                isset($category_seven_name) &&
                isset($category_seven_amount) &&
                isset($category_seven_currency_name) &&
                isset($category_seven_winners) &&
                isset($category_eight_name) &&
                isset($category_eight_amount) &&
                isset($category_eight_currency_name) &&
                isset($category_eight_winners) &&
                isset($category_nine_name) &&
                isset($category_nine_amount) &&
                isset($category_nine_currency_name) &&
                isset($category_nine_winners) &&
                isset($category_ten_name) &&
                isset($category_ten_amount) &&
                isset($category_ten_currency_name) &&
                isset($category_ten_winners) &&
                isset($category_eleven_name) &&
                isset($category_eleven_amount) &&
                isset($category_eleven_currency_name) &&
                isset($category_eleven_winners) &&
                isset($category_twelve_name) &&
                isset($category_twelve_amount) &&
                isset($category_twelve_currency_name) &&
                isset($category_twelve_winners) &&
                isset($category_thirteen_name) &&
                isset($category_thirteen_amount) &&
                isset($category_thirteen_currency_name) &&
                isset($category_thirteen_winners)
            ) {
                $result =
                    $regular_number_0 .
                    $regular_number_1 .
                    $regular_number_2 .
                    $regular_number_3 .
                    $regular_number_4 .
                    $lucky_number_0 .
                    $lucky_number_1 .
                    $category_one_name .
                    $category_one_amount .
                    $category_one_currency_name .
                    $category_one_winners .
                    $category_two_name .
                    $category_two_amount .
                    $category_two_currency_name .
                    $category_two_winners .
                    $category_three_name .
                    $category_three_amount .
                    $category_three_currency_name .
                    $category_three_winners .
                    $category_four_name .
                    $category_four_amount .
                    $category_four_currency_name .
                    $category_four_winners .
                    $category_five_name .
                    $category_five_amount .
                    $category_five_currency_name .
                    $category_five_winners .
                    $category_six_name .
                    $category_six_amount .
                    $category_six_currency_name .
                    $category_six_winners .
                    $category_seven_name .
                    $category_seven_amount .
                    $category_seven_currency_name .
                    $category_seven_winners .
                    $category_eight_name .
                    $category_eight_amount .
                    $category_eight_currency_name .
                    $category_eight_winners .
                    $category_nine_name .
                    $category_nine_amount .
                    $category_nine_currency_name .
                    $category_nine_winners .
                    $category_ten_name .
                    $category_ten_amount .
                    $category_ten_currency_name .
                    $category_ten_winners .
                    $category_eleven_name .
                    $category_eleven_amount .
                    $category_eleven_currency_name .
                    $category_eleven_winners .
                    $category_twelve_name .
                    $category_twelve_amount .
                    $category_twelve_currency_name .
                    $category_twelve_winners .
                    $category_thirteen_name .
                    $category_thirteen_amount .
                    $category_thirteen_currency_name .
                    $category_thirteen_winners;
            }
        } else {
            $result == NULL;
        }

        return $result;
    }

}