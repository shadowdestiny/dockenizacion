<?php


namespace EuroMillions\services;


use Doctrine\ORM\EntityManager;

use EuroMillions\entities\Bet;
use EuroMillions\entities\EuroMillionsDraw;
use EuroMillions\entities\PlayConfig;
use EuroMillions\entities\User;
use EuroMillions\repositories\BetRepository;
use EuroMillions\repositories\PlayConfigRepository;
use EuroMillions\vo\EuroMillionsLine;
use EuroMillions\vo\ServiceActionResult;

class PlayService
{

    private $entityManager;

    /**
     * @var PlayConfigRepository
     */
    private $playConfigRepository;

    /** @var  LotteriesDataService */
    private $lotteriesDataService;

    /** @var  BetRepository */
    private $betRepository;

    private $lotteryRepository;

    public function __construct(EntityManager $entityManager, LotteriesDataService $lotteriesDataService)
    {
        $this->entityManager = $entityManager;
        $this->playConfigRepository = $entityManager->getRepository('EuroMillions\entities\PlayConfig');
        $this->betRepository = $entityManager->getRepository('EuroMillions\entities\Bet');
        $this->lotteryRepository = $this->entityManager->getRepository('EuroMillions\entities\Lottery');
        $this->lotteriesDataService = $lotteriesDataService;
    }

    /**
     * @param User $user
     * @param EuroMillionsLine $euromillionsLine
     * @return ServiceActionResult
     * @internal param EuroMillionsLine $euromillionsResult
     */
    public function play(User $user, EuroMillionsLine $euromillionsLine)
    {
        if($user->getBalance()->getAmount() > 0){

            //EMTD we need get params from view form
            $playConfig = new PlayConfig();
            $playConfig->initialize([
                    'user' => $user,
                    'line' => $euromillionsLine
                ]
            );
            try {
                $this->playConfigRepository->add($playConfig);
                $this->entityManager->flush($playConfig);
            } catch(\Exception $e){
                return new ServiceActionResult(false, 'An exception occurred while created play');
            }
            return new ServiceActionResult(true);
        } else {
            return new ServiceActionResult(false);
        }
    }

    /**
     * @param PlayConfig $playConfig
     * @param EuroMillionsDraw $euroMillionsDraw
     * @param \DateTime $today
     * @return ServiceActionResult
     */
    public function bet(PlayConfig $playConfig, EuroMillionsDraw $euroMillionsDraw, \DateTime $today = null)
    {
        if (!$today) {
            $today = new \DateTime();
        }

        $dateNextDraw = $this->lotteriesDataService->getNextDrawByLottery('EuroMillions', $today);
        $result = $this->betRepository->getBetsByDrawDate(new \DateTime($dateNextDraw));
        if(!empty($result)){
            return new ServiceActionResult(true);
        }else{
            $bet = new Bet($playConfig,$euroMillionsDraw);
            $this->betRepository->add($bet);
            $this->entityManager->flush($bet);
            return new ServiceActionResult(true);
        }
    }
}