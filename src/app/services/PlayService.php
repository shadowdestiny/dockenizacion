<?php


namespace EuroMillions\services;


use Doctrine\ORM\EntityManager;

use EuroMillions\entities\Bet;
use EuroMillions\entities\EuroMillionsDraw;
use EuroMillions\entities\PlayConfig;
use EuroMillions\entities\User;
use EuroMillions\interfaces\IPlayStorageStrategy;
use EuroMillions\repositories\BetRepository;
use EuroMillions\repositories\PlayConfigRepository;
use EuroMillions\vo\EuroMillionsLine;
use EuroMillions\vo\PlayForm;
use EuroMillions\vo\PlayFormToStorage;
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

    private $playStorageStrategy;

    public function __construct(EntityManager $entityManager, LotteriesDataService $lotteriesDataService, IPlayStorageStrategy $playStorageStrategy)
    {
        $this->entityManager = $entityManager;
        $this->playConfigRepository = $entityManager->getRepository('EuroMillions\entities\PlayConfig');
        $this->betRepository = $entityManager->getRepository('EuroMillions\entities\Bet');
        $this->lotteryRepository = $this->entityManager->getRepository('EuroMillions\entities\Lottery');
        $this->lotteriesDataService = $lotteriesDataService;
        $this->playStorageStrategy = $playStorageStrategy;
    }

    /**
     * @param User $user
     * @return ServiceActionResult
     */
    public function play(User $user)
    {
        //EMTD previously should check amount or userservice deduct amount about his balance
        if($user->getBalance()->getAmount() > 0){
            try {
                $temporalForm = $this->playStorageStrategy->findByKey($user->getId()->id());
                if(empty($temporalForm)){
                    return new ServiceActionResult(false,'The search key doesn\'t exist');
                }
                $playConfig = new PlayConfig();
                $playConfig->formToEntity($user,$temporalForm);
                $this->playConfigRepository->add($user, $playConfig);
                $this->entityManager->flush($playConfig);
                //Remove play storage
                $result = $this->playStorageStrategy->delete($user->getId()->id());
                if(!empty($result)){
                    return new ServiceActionResult(true);
                }else{
                    return new ServiceActionResult(false);
                }
            } catch(\Exception $e){
                return new ServiceActionResult(false, 'An exception occurred while created play');
            }
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

    public function temporarilyStorePlay(PlayFormToStorage $playForm)
    {
        $result = $this->playStorageStrategy->saveAll($playForm);
        if($result->success()){
            return new ServiceActionResult(true);
        }else{
            return new ServiceActionResult(false);
        }

    }

}