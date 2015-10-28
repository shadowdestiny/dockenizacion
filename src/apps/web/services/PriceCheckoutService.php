<?php


namespace EuroMillions\web\services;


use Doctrine\ORM\EntityManager;
use EuroMillions\web\entities\User;
use EuroMillions\web\repositories\BetRepository;
use EuroMillions\web\repositories\PlayConfigRepository;
use EuroMillions\web\repositories\UserRepository;
use EuroMillions\web\vo\ActionResult;
use Money\Money;

class PriceCheckoutService
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

    /** @var UserRepository  */
    private $userRespository;


    public function __construct(EntityManager $entityManager, LotteriesDataService $lotteriesDataService)
    {
        $this->entityManager = $entityManager;
        $this->playConfigRepository = $entityManager->getRepository('EuroMillions\web\entities\PlayConfig');
        $this->betRepository = $entityManager->getRepository('EuroMillions\web\entities\Bet');
        $this->userRespository = $entityManager->getRepository('EuroMillions\web\entities\User');
        $this->lotteriesDataService = $lotteriesDataService;
    }

    public function playConfigsWithBetsAwarded(\DateTime $date)
    {
        if(!$date) {
            $date = new \DateTime();
        }

        $result_awarded = $this->betRepository->getCheckResult($date->format('Y-m-d'));
        if(!empty($result_awarded)){
            return new ActionResult(true,$result_awarded);
        }else{
            return new ActionResult(false);
        }
    }

    public function reChargeAmountAwardedToUser(User $user, Money $amount)
    {
        if($amount->getAmount() > 0) {
            try{
                $user->setBalance($user->getBalance()->add($amount));
                $this->userRespository->add($user);
                $this->entityManager->flush($user);
                return new ActionResult(true);
            }catch(\Exception $e){
                return new ActionResult(false);
            }
        }
    }



}