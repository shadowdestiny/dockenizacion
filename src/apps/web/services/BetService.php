<?php

namespace EuroMillions\web\services;

use Doctrine\ORM\EntityManager;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\components\CypherCastillo3DES;
use EuroMillions\web\components\CypherCastillo3DESLive;
use EuroMillions\web\entities\Bet;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\entities\LogValidationApi;
use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\entities\User;
use EuroMillions\web\exceptions\InvalidBalanceException;
use EuroMillions\web\repositories\BetRepository;
use EuroMillions\web\repositories\LogValidationApiRepository;
use EuroMillions\web\repositories\PlayConfigRepository;
use EuroMillions\web\services\external_apis\LotteryValidationCastilloApi;
use EuroMillions\web\services\external_apis\LotteryValidationCastilloChristmasApi;
use EuroMillions\web\vo\CastilloCypherKey;
use EuroMillions\web\vo\CastilloTicketId;

class BetService
{
    private $entityManager;

    private $userRepository;

    /** @var  BetRepository */
    private $betRepository;

    /** @var LogValidationApiRepository $logValidationRepository */
    private $logValidationRepository;

    /** @var PlayConfigRepository $playConfigRepository */
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

        if (!$lotteryValidation) {
            $lotteryValidation = new LotteryValidationCastilloApi();
        }
        /** @var User $user */
        $user = $this->userRepository->find($playConfig->getUser()->getId());
        $single_bet_price = $euroMillionsDraw->getLottery()->getSingleBetPrice();
        if (($user->getBalance()->getAmount() || $user->getWallet()->getSubscription()) >= $single_bet_price->getAmount()) {
            $di = \Phalcon\Di::getDefault();
            $cypher = $di->get('environmentDetector')->get() != 'production' ? new CypherCastillo3DES() : new CypherCastillo3DESLive();
            try {
                $bet = new Bet($playConfig, $euroMillionsDraw);
                $castillo_key = CastilloCypherKey::create();
                $castillo_ticket = CastilloTicketId::create();
                $bet->setCastilloBet($castillo_ticket);
                $result_validation = $lotteryValidation->validateBet($bet, $cypher, $castillo_key, $castillo_ticket, $dateNextDraw, $bet->getPlayConfig()->getLine());
                $log_api_reponse = new LogValidationApi();
                $log_api_reponse->initialize([
                    'id_provider' => 1,
                    'id_ticket' => $lotteryValidation->getCastilloId(),//$lotteryValidation->getXmlResponse()->id,
                    'status' => 'OK',//$lotteryValidation->getXmlResponse()->status,
                    'response' => '',//$lotteryValidation->getXmlResponse(),
                    'received' => new \DateTime(),
                    'bet' => $bet
                ]);
                $this->entityManager->persist($bet);
                $this->logValidationRepository->add($log_api_reponse);

                if ($result_validation->success()) {
                    $this->betRepository->add($bet);
                    $this->playConfigRepository->add($playConfig);
                    $this->entityManager->flush();
                    return new ActionResult(true);
                } else {
                    return new ActionResult(false, $result_validation->errorMessage());
                }
            } catch (\Exception $e) {
                return new ActionResult(false);
            }
        } else {
            throw new InvalidBalanceException();
        }
    }

    public function validationLottery(PlayConfig $playConfig, EuroMillionsDraw $euroMillionsDraw, \DateTime $dateNextDraw, \DateTime $today = null, $uuid = null)
    {
        if (!$today) {
            $today = new \DateTime();
        }
        /** @var User $user */
        $user = $this->userRepository->find($playConfig->getUser()->getId());
        $single_bet_price = $euroMillionsDraw->getLottery()->getSingleBetPrice();
        if (($user->getBalance()->getAmount() || $user->getWallet()->getSubscription()) >= $single_bet_price->getAmount()) {
            try {
                $bet = new Bet($playConfig, $euroMillionsDraw);
                $log_api_reponse = new LogValidationApi();
                $log_api_reponse->initialize([
                    'id_provider' => 2,
                    'id_ticket' => 0,//$lotteryValidation->getXmlResponse()->id,
                    'status' => 'OK',//$lotteryValidation->getXmlResponse()->status,
                    'response' => '',//$lotteryValidation->getXmlResponse(),
                    'received' => new \DateTime(),
                    'bet' => $bet,
                    'uuid' => $uuid
                ]);

                $this->entityManager->persist($bet);
                $this->logValidationRepository->add($log_api_reponse);
                if ($uuid) {
                    $this->betRepository->add($bet);
                    $this->playConfigRepository->add($playConfig);
                    $this->entityManager->flush();
                    return new ActionResult(true);
                } else {
                    return new ActionResult(false);
                }
            } catch (\Exception $e) {
                return new ActionResult(false);
            }
        } else {
            throw new InvalidBalanceException();
        }
    }

    public function validationChristmas(PlayConfig $playConfig, EuroMillionsDraw $euroMillionsDraw, \DateTime $dateNextDraw, \DateTime $today = null, LotteryValidationCastilloChristmasApi $lotteryValidation = null)
    {
        if (!$today) {
            $today = new \DateTime();
        }

        if (!$lotteryValidation) {
            $lotteryValidation = new LotteryValidationCastilloChristmasApi();
        }
        /** @var User $user */
        $user = $this->userRepository->find($playConfig->getUser()->getId());
        if ($user->getBalance()->getAmount()) {
            $di = \Phalcon\Di::getDefault();
            $cypher = new CypherCastillo3DESLive();
            try {
                $bet = new Bet($playConfig, $euroMillionsDraw);
                $castillo_key = CastilloCypherKey::create();
                $castillo_ticket = CastilloTicketId::create();
                $bet->setCastilloBet($castillo_ticket);
                $result_validation = $lotteryValidation->validateBet($bet, $cypher, $castillo_key, $castillo_ticket, $dateNextDraw, $bet->getPlayConfig()->getLine());

                $log_api_reponse = new LogValidationApi();
                $log_api_reponse->initialize([
                    'id_provider' => 1,
                    'id_ticket' => $lotteryValidation->getCastilloId(),//$lotteryValidation->getXmlResponse()->id,
                    'status' => $result_validation->success() ? 'OK' : 'KO',//$lotteryValidation->getXmlResponse()->status,
                    'response' => '',//$lotteryValidation->getXmlResponse(),
                    'received' => new \DateTime(),
                    'bet' => $bet
                ]);
                $this->entityManager->persist($bet);
                $this->logValidationRepository->add($log_api_reponse);
                $this->entityManager->flush();


                if ($result_validation->success()) {
                    $this->betRepository->add($bet);
                    $this->entityManager->flush();
                    $this->playConfigRepository->add($playConfig);
                    $this->entityManager->flush();
                    return new ActionResult(true);
                } else {
                    return new ActionResult(false, $result_validation->errorMessage());
                }
            } catch (\Exception $e) {
                return new ActionResult(false);
            }
        } else {
            throw new InvalidBalanceException();
        }
    }

    public function groupingValidation(array $playConfig,
                                       EuroMillionsDraw $euroMillionsDraw,
                                       \DateTime $dateNextDraw,
                                       LotteryValidationCastilloApi $lotteryValidation = null)
    {
        if (!$lotteryValidation) {
            $lotteryValidation = new LotteryValidationCastilloApi();
        }
        /** @var User $user */
        $user = $this->userRepository->find($playConfig[0]->getUser()->getId());
        $single_bet_price = $euroMillionsDraw->getLottery()->getSingleBetPrice();
        if ($user->getBalance()->getAmount() >= $single_bet_price->getAmount()) {
            $di = \Phalcon\Di::getDefault();
            $cypher = $di->get('environmentDetector')->get() != 'production' ? new CypherCastillo3DES() : new CypherCastillo3DESLive();
            try {
                $result_validation = $lotteryValidation->validateBetInGroup($cypher, $dateNextDraw, $playConfig);
                if ($result_validation->success()) {
                    $this->logValidationRepository->persistValidationsAndBetsFromPlayConfigsCollection($playConfig, $euroMillionsDraw, $lotteryValidation->getCastilloId());
                    return $result_validation;
                }

            } catch (\Exception $e) {
                return new ActionResult(false);
            }
        } else {
            throw new InvalidBalanceException();
        }
    }

    public function getBetsPlayedLastDraw(\DateTime $dateLastDraw)
    {
        try {
            $result = $this->betRepository->getBetsPlayedLastDraw($dateLastDraw);
            if (count($result) > 0) {
                return $result;
            }
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function obtainBetsWithAPlayConfigAndAEuromillionsDraw(PlayConfig $playConfig, EuroMillionsDraw $euroMillionsDraw)
    {
        try {
            return $this->betRepository->getBetsByPlayConfigAndEuromillionsDraw($playConfig, $euroMillionsDraw);
        } catch (\Exception $e) {
            return false;
        }
    }

    public function updatePlayConfigsToInactive(\DateTime $dateTime, $lottery)
    {
        try {
            $this->playConfigRepository->updateToInactives($dateTime, $lottery);
        } catch (\Exception $e) {
            throw new \Exception('Error updating playconfigs actives');
        }
    }

    public function getBet($id)
    {
        return $this->betRepository->findOneBy(['id' => $id]);
    }
}