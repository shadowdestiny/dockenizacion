<?php
namespace EuroMillions\services;

use Doctrine\ORM\EntityManager;
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
    protected $apisFactory;
    /** @var EntityManager|\PHPUnit_Framework_MockObject_MockObject  */
    protected $entityManager;

    public function __construct(EntityManager $entityManager = null, LotteryApisFactory $apisFactory = null)
    {
        parent::__construct();
        $this->entityManager = $entityManager ? $entityManager : $this->di->get('entityManager');
        $this->lotteryDrawRepository = $this->entityManager->getRepository('EuroMillions\entities\LotteryDraw');
        $this->lotteryRepository = $this->entityManager->getRepository('EuroMillions\entities\Lottery');
        $this->apisFactory = $apisFactory ? $apisFactory : new LotteryApisFactory();
    }

    public function updateNextDrawJackpot($lotteryName, $now = null, Curl $curlWrapper = null)
    {
        if (!$now) {
            $now = date("Y-m-d H:i:s");
        }
        /** @var Lottery $lottery */
        $lottery = $this->lotteryRepository->findOneBy(['name' => $lotteryName]);
        $next_draw_date = $lottery->getNextDrawDate($now);
        $jackpot_api = $this->apisFactory->jackpotApi($lottery);
        $jackpot = $jackpot_api->getJackpotForDate($lotteryName, $next_draw_date->format("Y-m-d"), $curlWrapper);
        /** @var LotteryDraw $draw */
        $draw = $this->lotteryDrawRepository->findOneBy(['lottery' => $lottery, 'draw_date' => $next_draw_date]);
        if (!$draw) {
            $draw = new LotteryDraw();
            $draw->initialize([
                'draw_date'  => $next_draw_date,
                'jackpot'    => $jackpot,
                'message'    => '',
                'big_winner' => 0,
                'published' => 0,
                'lottery' => $lottery
            ]);
        } else {
            $draw->setJackpot($jackpot);
        }
        $this->entityManager->persist($draw);
        $this->entityManager->flush();
    }

    public function getNextJackpot()
    {
        return $this->lotteryDrawRepository->getNextJackpot('EuroMillions');
    }
}