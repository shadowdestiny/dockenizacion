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
use Phalcon\Logger\Adapter\File as FileAdapter;

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
        } catch (\Exception $e) {
            $result = $result_api->getRaffleForDateSecond($lotteryName, $last_draw_date->format('Y-m-d'));
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
        $this->createLog("[updateNextDrawJackpot] [lottery: " . $lottery->getName() . "] [next_draw_date: " . $next_draw_date->format('Y-m-d H:i:s') . "]", \Phalcon\Logger::INFO);
        try {
            $jackpot_api = $this->apisFactory->jackpotApi($lottery);
            try {
                $jackpot = $jackpot_api->getJackpotForDate($lotteryName, $next_draw_date->format("Y-m-d"));
                $this->createLog("[updateNextDrawJackpot] [loteriasyapuestas.es]: " . $jackpot->getAmount(), \Phalcon\Logger::INFO);
            } catch ( ValidDateRangeException $e ) {
                $jackpot = $jackpot_api->getJackpotForDateSecond($lotteryName, $next_draw_date->format("Y-m-d"));
                $this->createLog("[updateNextDrawJackpot] [euromillions.p.mashape.com]: " . $jackpot->getAmount(), \Phalcon\Logger::INFO);
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
        try {
            /** @var Lottery $lottery */
            $lottery = $this->lotteryRepository->findOneBy(['name' => $lotteryName]);
            $result_api = $this->apisFactory->resultApi($lottery);
            $last_draw_date = $lottery->getLastDrawDate($now);

            $result = $result_api->getResultForDate($lotteryName, $last_draw_date->format('Y-m-d'));
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
            $result = $result_api->getResultForDateSecond($lotteryName, $last_draw_date->format('Y-m-d'));
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
            //throw new \Exception('Error updating results');
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
            /** @var EuroMillionsDraw $draw */
            $draw = $this->lotteryDrawRepository->findOneBy(['lottery' => $lottery, 'draw_date' => $last_draw_date]);
            $draw->createBreakDown($result);
            $this->entityManager->flush();
            return $draw;
        } catch (\Exception $e) {
            $result = $result_api->getResultBreakDownForDateSecond($lotteryName, $last_draw_date->format('Y-m-d'));
            /** @var EuroMillionsDraw $draw */
            $draw = $this->lotteryDrawRepository->findOneBy(['lottery' => $lottery, 'draw_date' => $last_draw_date]);
            $draw->createBreakDown($result);
            $this->entityManager->flush();
            return $draw;
            //throw new \Exception('Error updating results');
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
}