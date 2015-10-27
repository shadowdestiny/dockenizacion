<?php


namespace EuroMillions\services;


use Doctrine\ORM\EntityManager;
use EuroMillions\entities\User;
use EuroMillions\repositories\BetRepository;
use EuroMillions\repositories\PlayConfigRepository;
use EuroMillions\repositories\UserRepository;
use EuroMillions\vo\ActionResult;
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
        $this->playConfigRepository = $entityManager->getRepository('EuroMillions\entities\PlayConfig');
        $this->betRepository = $entityManager->getRepository('EuroMillions\entities\Bet');
        $this->userRespository = $entityManager->getRepository('EuroMillions\entities\User');
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