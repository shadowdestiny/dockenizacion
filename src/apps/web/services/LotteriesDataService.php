<?php
namespace EuroMillions\web\services;

use Doctrine\ORM\EntityManager;
use EuroMillions\web\entities\Lottery;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\repositories\LotteryDrawRepository;
use EuroMillions\web\repositories\LotteryRepository;
use EuroMillions\web\services\external_apis\LotteryApisFactory;
use EuroMillions\web\vo\EuroMillionsDrawBreakDown;
use EuroMillions\web\vo\EuroMillionsLine;
use EuroMillions\web\vo\ActionResult;
use Money\Money;
use Phalcon\Http\Client\Provider\Curl;

class LotteriesDataService
{
    /** @var  LotteryDrawRepository */
    protected $lotteryDrawRepository;
    /** @var  LotteryRepository*/
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

    public function getNextDateDrawByLottery($lotteryName, $now = null)
    {
        if (!$now) {
            $now = new \DateTime();
        }
        /** @var Lottery $lottery */
        $lottery = $this->lotteryRepository->findOneBy(['name' => $lotteryName]);
        $nextDrawDate = $lottery->getNextDrawDate($now);
        return $nextDrawDate;
    }

    public function getNextDrawByLottery($lotteryName, $now = null)
    {
        if(!$now){
            $now = new \DateTime();
        }
        $lottery = $this->lotteryRepository->findOneBy(['name' => $lotteryName]);
        if(!empty($lottery)){
            $euroMillionsDraw = $this->lotteryDrawRepository->getNextDraw($lottery,$now);
            if(!empty($euroMillionsDraw)){
                return new ActionResult(true,$euroMillionsDraw);
            }else{
                return new ActionResult(false);
            }
        }
    }

    public function getLotteryConfigByName($lotteryName)
    {
        /** @var Lottery $lottery */
        $lottery = $this->lotteryRepository->findOneBy(['name' => $lotteryName]);
        if(!empty($lottery)){
            return new ActionResult(true, $lottery);
        }else{
            return new ActionResult(false,'Lottery unknown');
        }
    }

    public function getBreakDownDrawByDate($lotteryName, \DateTime $now = null)
    {
        /** @var Lottery $lottery */
        $lottery = $this->lotteryRepository->findOneBy(['name' => $lotteryName]);
        if(!empty($lottery)){
            /** @var EuroMillionsDrawBreakDown $emBreakDownData */
            $emBreakDownData = $this->lotteryDrawRepository->getBreakDownData($lottery);
            if(!empty($emBreakDownData)){
                return new ActionResult(true, $emBreakDownData);
            }else{
                return new ActionResult(false);
            }
        }else{
            return new ActionResult(false);
        }
    }

    public function updateLastBreakDown($lotteryName, \DateTime $now = null, Curl $curlWrapper = null)
    {
        if (!$now) {
            $now = new \DateTime();
        }
        /** @var Lottery $lottery */
        $lottery = $this->lotteryRepository->findOneBy(['name' => $lotteryName]);
        $result_api = $this->apisFactory->resultApi($lottery, $curlWrapper);
        $last_draw_date = $lottery->getLastDrawDate($now);
        $result = $result_api->getResultBreakDownForDate($lotteryName, $last_draw_date->format('Y-m-d'));
        $draw = $this->lotteryDrawRepository->findOneBy(['lottery' => $lottery, 'draw_date' =>$last_draw_date]);
        $draw->createBreakDown($result);
        $this->entityManager->flush();
    }

    public function lastBreakDown($lotteryName, \DateTime $now = null) {
        if(!$now) {
            $now = new \DateTime();
        }
        /** @var Lottery $lottery */
        $lottery = $this->lotteryRepository->findOneBy(['name' => $lotteryName]);
        $last_draw_date = $lottery->getLastDrawDate($now);
        /** @var EuroMillionsDraw $draw */
        $draw = $this->lotteryDrawRepository->findOneBy(['lottery' => $lotteryName, 'draw_date' => $last_draw_date]);
        if(!empty($draw)) {
            return new ActionResult(true,$draw);
        }else{
            return new ActionResult(false);
        }
    }

}