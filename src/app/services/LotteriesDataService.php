<?php
namespace EuroMillions\services;

use Doctrine\ORM\EntityManager;
use EuroMillions\entities\EuroMillionsResult;
use EuroMillions\entities\Lottery;
use EuroMillions\entities\LotteryDraw;
use EuroMillions\repositories\LotteryDrawRepository;
use EuroMillions\services\external_apis\LotteryApisFactory;
use Phalcon\Http\Client\Provider\Curl;

class LotteriesDataService extends PhalconService
{
    /** @var  LotteryDrawRepository */
    protected $lotteryDrawRepository;
    protected $lotteryRepository;
    protected $lotteryResultRepository;
    protected $apisFactory;
    /** @var EntityManager|\PHPUnit_Framework_MockObject_MockObject */
    protected $entityManager;

    public function __construct(EntityManager $entityManager = null, LotteryApisFactory $apisFactory = null)
    {
        parent::__construct();
        $this->entityManager = $entityManager ? $entityManager : $this->di->get('entityManager');
        $this->lotteryDrawRepository = $this->entityManager->getRepository('EuroMillions\entities\LotteryDraw');
        $this->lotteryRepository = $this->entityManager->getRepository('EuroMillions\entities\Lottery');
        $this->lotteryResultRepository = $this->entityManager->getRepository('EuroMillions\entities\LotteryResult');
        $this->apisFactory = $apisFactory ? $apisFactory : new LotteryApisFactory();
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
        /** @var LotteryDraw $draw */
        $draw = $this->lotteryDrawRepository->findOneBy(['lottery' => $lottery, 'draw_date' => $next_draw_date]);
        if (!$draw) {
            $draw = new LotteryDraw();
            $draw->initialize([
                'draw_date'  => $next_draw_date,
                'jackpot'    => $jackpot,
                'message'    => '',
                'big_winner' => 0,
                'published'  => 0,
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
        $lottery_result = new EuroMillionsResult();
        $lottery_result->initialize([
            'regular_numbers' => implode(',',$result['regular_numbers']),
            'lucky_numbers' => implode(',',$result['lucky_numbers']),
        ]);
        $this->entityManager->persist($lottery_result);
        $draw->setResult($lottery_result);
        $this->entityManager->flush();

    }

    public function getNextJackpot($lotteryName)
    {
        return $this->lotteryDrawRepository->getNextJackpot($lotteryName);
    }

    public function getLastResult($lotteryName)
    {
        /** @var Lottery $lottery */
        $lottery = $this->lotteryRepository->findOneBy(['name' => $lotteryName]);
        /** @var EuroMillionsResult $lottery_result */
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