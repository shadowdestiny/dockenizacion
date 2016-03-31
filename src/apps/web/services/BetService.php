<?php
namespace EuroMillions\web\services;

use Doctrine\ORM\EntityManager;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\components\CypherCastillo3DES;
use EuroMillions\web\entities\Bet;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\entities\LogValidationApi;
use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\entities\User;
use EuroMillions\web\exceptions\InvalidBalanceException;
use EuroMillions\web\repositories\BetRepository;
use EuroMillions\web\services\external_apis\LotteryValidationCastilloApi;
use EuroMillions\web\vo\CastilloCypherKey;
use EuroMillions\web\vo\CastilloTicketId;

class BetService
{
    private $entityManager;

    private $userRepository;

    /** @var  BetRepository */
    private $betRepository;

    private $logValidationRepository;

    private $playConfigRepository;


    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $entityManager->getRepository('EuroMillions\web\entities\User');
        $this->betRepository = $entityManager->getRepository('EuroMillions\web\entities\Bet');
        $this->logValidationRepository = $entityManager->getRepository('EuroMillions\web\entities\LogValidationApi');
        $this->playConfigRepository = $entityManager->getRepository('EuroMillions\web\entities\PlayConfig');
    }


    public function validation(PlayConfig $playConfig, EuroMillionsDraw $euroMillionsDraw, \DateTime $dateNextDraw, \DateTime $today = null, LotteryValidationCastilloApi $lotteryValidation = null)
    {
        if (!$today) {
            $today = new \DateTime();
        }

        if(!$lotteryValidation) {
            $lotteryValidation = new LotteryValidationCastilloApi();
        }
        /** @var User $user */
        $user = $this->userRepository->find($playConfig->getUser()->getId());
        $single_bet_price = $euroMillionsDraw->getLottery()->getSingleBetPrice();
        if($user->getBalance()->getAmount() > $single_bet_price->getAmount()) {
            $result = $this->betRepository->getBetsByDrawDate($dateNextDraw);
            try{
                $bet = new Bet($playConfig,$euroMillionsDraw);
                $castillo_key = CastilloCypherKey::create();
                $castillo_ticket = CastilloTicketId::create();
                $bet->setCastilloBet($castillo_ticket);
                $result_validation = $lotteryValidation->validateBet($bet,new CypherCastillo3DES(),$castillo_key,$castillo_ticket,$dateNextDraw, $bet->getPlayConfig()->getLine());
                $log_api_reponse = new LogValidationApi();
                $log_api_reponse->initialize([
                    'id_provider' => 1,
                    'id_ticket' => $lotteryValidation->getXmlResponse()->id,
                    'status' => $lotteryValidation->getXmlResponse()->status,
                    'response' => $lotteryValidation->getXmlResponse(),
                    'received' => new \DateTime()
                ]);
                $this->logValidationRepository->add($log_api_reponse);
                $this->entityManager->flush($log_api_reponse);
                if($result_validation->success()) {
                    $this->betRepository->add($bet);
                    $this->entityManager->flush();
                    $user->payPreservingWinnings($single_bet_price);
                    $this->userRepository->add($user);
                    $this->playConfigRepository->add($playConfig);
                    $this->entityManager->flush();
                    return new ActionResult(true);
                } else{
                    return new ActionResult(false, $result_validation->errorMessage());
                }
            }catch(\Exception $e) {
                return new ActionResult(false);
            }
        } else {
            throw new InvalidBalanceException();
        }
    }

}