<?php
namespace EuroMillions\services;

use Doctrine\ORM\EntityManager;
use EuroMillions\entities\Lottery;
use EuroMillions\entities\EuroMillionsDraw;
use EuroMillions\repositories\LotteryDrawRepository;
use EuroMillions\services\external_apis\LotteryApisFactory;
use EuroMillions\vo\EuroMillionsLine;
use Money\Money;
use Phalcon\Http\Client\Provider\Curl;

class LotteriesDataService
{
    /** @var  LotteryDrawRepository */
    protected $lotteryDrawRepository;
    protected $lotteryRepository;
    protected $apisFactory;
    protected $entityManager;

    public function __construct(EntityManager $entityManager, LotteryApisFactory $apisFactory)
    {
        $this->entityManager = $entityManager;
        $this->lotteryDrawRepository = $this->entityManager->getRepository('EuroMillions\entities\EuroMillionsDraw');
        $this->lotteryRepository = $this->entityManager->getRepository('EuroMillions\entities\Lottery');
        $this->apisFactory = $apisFactory;
    }

    public function updateNextDrawJackpot($lotteryName, \DateTime $now = null, Curl $curlWrapper = null)
    {
        if (!$now) {
            $now = new \DateTime();
        }
        /** @var Lottery $lottery */
        $lottery = $this->lotteryRepository->findOneBy(['name' => $lotteryName]);
        $next_draw_date = $lottery->getNextDrawDate($now);
        $jackpot_api = $this->apisFactory->jackpotApi($lottery, $curlWrapper);
        $jackpot = $jackpot_api->getJackpotForDate($lotteryName, $next_draw_date->format("Y-m-d"));
        /** @var EuroMillionsDraw $draw */
        $draw = $this->lotteryDrawRepository->findOneBy(['lottery' => $lottery, 'draw_date' => $next_draw_date]);
        if (!$draw) {
            $draw = new EuroMillionsDraw();
            $draw->initialize([
                'draw_date'  => $next_draw_date,
                'jackpot'    => $jackpot,
                'lottery'    => $lottery
            ]);
        } else {
            $draw->setJackpot($jackpot);
        }
        $this->entityManager->persist($draw);
        $this->entityManager->flush();
    }

    public function updateLastDrawResult($lotteryName, \DateTime $now = null, Curl $curlWrapper = null)
    {
        if (!$now) {
            $now = new \DateTime();
        }
        /** @var Lottery $lottery */
        $lottery = $this->lotteryRepository->findOneBy(['name' => $lotteryName]);
        $result_api = $this->apisFactory->resultApi($lottery, $curlWrapper);
        $last_draw_date = $lottery->getLastDrawDate($now);
        $result = $result_api->getResultForDate($lotteryName, $last_draw_date->format('Y-m-d'));
        $draw = $this->lotteryDrawRepository->findOneBy(['lottery' => $lottery, 'draw_date' =>$last_draw_date]);
        $draw->createResult($result['regular_numbers'], $result['lucky_numbers']);
        $this->entityManager->flush();

    }

    public function getLastDrawDate($lotteryName, $today = null)
    {
        if (!$today) {
            $today = new \DateTime();
        }
        return $this->lotteryRepository->findOneby(['name' => $lotteryName])->getLastDrawDate($today);
    }

    /**
     * @param $lotteryName
     * @return Money
     */
    public function getNextJackpot($lotteryName)
    {
        return $this->lotteryDrawRepository->getNextJackpot($lotteryName);
    }

    public function getLastResult($lotteryName)
    {
        /** @var Lottery $lottery */
        $lottery = $this->lotteryRepository->findOneBy(['name' => $lotteryName]);
        /** @var EuroMillionsLine $lottery_result */
        $lottery_result = $this->lotteryDrawRepository->getLastResult($lottery);
        $result['regular_numbers'] = explode(',',$lottery_result->getRegularNumbers());
        $result['lucky_numbers'] = explode(',',$lottery_result->getLuckyNumbers());
        return $result;
    }

    public function getTimeToNextDraw($lotteryName, $now = null)
    {
        if (!$now) {
            $now = new \DateTime();
        }
        /** @var Lottery $lottery */
        $lottery = $this->lotteryRepository->findOneBy(['name' => $lotteryName]);
        $next_draw_date = $lottery->getNextDrawDate($now);
        return $now->diff($next_draw_date);
    }
}