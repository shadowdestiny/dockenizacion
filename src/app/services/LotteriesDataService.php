<?php
namespace EuroMillions\services;

use Doctrine\ORM\EntityManager;
use EuroMillions\entities\Lottery;
use EuroMillions\entities\LotteryDraw;
use EuroMillions\repositories\LotteryDrawRepository;

class LotteriesDataService
{
    /** @var  LotteryDrawRepository */
    protected $entityManager;
    protected $lotteryDrawRepository;
    protected $lotteryRepository;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->lotteryDrawRepository = $entityManager->getRepository('EuroMillions\entities\LotteryDraw');
        $this->lotteryRepository = $entityManager->getRepository('EuroMillions\entities\Lottery');
    }

    public function updateNextDrawJackpot($lotteryName, $now = null)
    {
        if (!$now) {
            $now = date("Y-m-d H:i:s");
        }
        /** @var Lottery $lottery */
        $lottery = $this->lotteryRepository->findOneBy(['name' => $lotteryName]);
        $next_draw_date = $lottery->getNextDrawDate($now)->format("Y-m-d");
        $draw = $this->lotteryDrawRepository->findOneBy(['lottery' => $lottery, 'draw_date' => $next_draw_date]);
        if (!$draw) {
            $draw = new LotteryDraw();
            $draw->initialize([
                'draw_date'  => $next_draw_date,
                'jackpot'    => null,
                'message'    => '',
                'big_winner' => 0,
                'published' => 0,
                'lottery' => $lottery
            ]);
            $this->entityManager->persist($draw);
        }

    }

    public function updateResultsAndNextDraw($lotteryName, $resultApiFactory = null)
    {
//        if(!$resultApiFactory) {
//            $resultApiFactory = new ResultFactory();
//        }
//        $lottery = $this->lotteryRepository->findOneBy(['name' => $lotteryName]);
//        $result_api = $resultApiFactory->getApi($lottery);
//        $strategy = $resultApiFactory->getStrategy($lottery);
//        $lottery_result_data = $strategy->load($result_api->getResultForDate($lottery->getNextDrawDate()));
//        $result_model = $resultApiFactory->getResultModel($lottery, $result_model);
//        //EMTD store the result
//        //EMTEST crear test de integración para ver que se guarda todo en la bd como corresponde
    }

    public function getNextJackpot()
    {

    }

    public function getLastWinningNumbers()
    {

    }

    public function getTimeToNextDraw()
    {

    }
}