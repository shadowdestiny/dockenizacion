<?php
namespace EuroMillions\web\services;

use Doctrine\ORM\EntityManager;
use EuroMillions\web\entities\Lottery;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\exceptions\DataMissingException;
use EuroMillions\web\exceptions\ValidDateRangeException;
use EuroMillions\web\repositories\LotteryDrawRepository;
use EuroMillions\web\repositories\LotteryRepository;
use EuroMillions\web\services\external_apis\LotteryApisFactory;
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
            $jackpot = new Money(15000000, new Currency('EUR'));
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
            throw new \Exception('Error updating results');
        }
    }

    /**
     * @param $lotteryName
     * @param \DateTime|null $now
     * @return EuroMillionsDraw
     */
    public function updateLastBreakDown($lotteryName, \DateTime $now = null)
    {
        if (!$now) {
            $now = new \DateTime();
        }
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