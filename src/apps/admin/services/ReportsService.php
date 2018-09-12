<?php

namespace EuroMillions\admin\services;

use Doctrine\ORM\EntityManager;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\entities\User;
use EuroMillions\web\interfaces\IReports;
use EuroMillions\web\repositories\LotteryDrawRepository;
use EuroMillions\web\repositories\TransactionRepository;
use EuroMillions\web\repositories\UserRepository;
use EuroMillions\shared\services\CurrencyConversionService;
use Money\Currency;
use Money\Money;
use Phalcon\Exception;

class ReportsService
{
    private $entityManager;
    /** @var IReports $reportsRepository */
    private $reportsRepository;
    /** @var UserRepository $userRepository */
    private $userRepository;
    /** @var TransactionRepository $transactionRepository */
    private $transactionRepository;
    /** @var  LotteryDrawRepository $lotteryDrawRepository */
    private $lotteryDrawRepository;


    public function __construct(IReports $iReports = null, EntityManager $entityManager,CurrencyConversionService $conversionService)
    {
        $this->entityManager = $entityManager;
        $this->reportsRepository = $iReports;
        $this->userRepository = $this->entityManager->getRepository('EuroMillions\web\entities\User');
        $this->lotteryRepository = $this->entityManager->getRepository('EuroMillions\web\entities\Lottery');
        $this->transactionRepository = $this->entityManager->getRepository('EuroMillions\web\entities\Transaction');
        $this->lotteryDrawRepository = $this->entityManager->getRepository('EuroMillions\web\entities\EuroMillionsDraw');
        $this->currencyConversionService = $conversionService;

    }

    /**
     * @param $userId
     *
     * @return null|object
     */
    public function getUserById($userId)
    {
        return $this->userRepository->find($userId);
    }

    /**
     * @param array $headerList
     * @param array $postData
     *
     * @return array
     */
    public function getPlayersReportsResultsByPostData(array $headerList, array $postData)
    {
        return $this->generateSQLByPlayersReports($headerList, $postData);
    }

    /**
     * @param array $postData
     *
     * @return array
     */
    public function getPlayersReportsKeys(array $postData)
    {
        $aPlayersReportsKeys = [];
        foreach ($postData as $postKey => $postValue) {
            if (substr($postKey, 0, 6) == 'check_') {
                $aPlayersReportsKeys[] = explode('_', $postKey)[1];
            }
        }
        return $aPlayersReportsKeys;
    }

    /**
     * @param $needGGR
     * @param $dateFrom
     * @param $dateTo
     *
     * @return array
     */
    public function getGGRPlayersByDates($needGGR, $dateFrom, $dateTo)
    {
        if ($needGGR) {
            $dateFrom = ($dateFrom) ? (new \DateTime($dateFrom))->setTime(0, 0, 0) : (new \DateTime('2016-01-01'))->setTime(0, 0, 0);
            $dateTo = ($dateTo) ? (new \DateTime($dateTo))->setTime(23, 59, 59) : (new \DateTime())->setTime(23, 59, 59);

            $ggrPlayers = $this->reportsRepository->getUserAndDataFromTransactionsBetweenDates(
                $dateFrom->format('Y-m-d H:i:s'),
                $dateTo->format('Y-m-d H:i:s')
            );

            $users = [];
            foreach ($ggrPlayers as $ggrPlayer) {
                if ($ggrPlayer['entity_type'] == 'ticket_purchase') {
                    $data = explode('#', $ggrPlayer['data']);

                    if (isset($data[6])) {
                        if ($data[6] > 0) {
                            $brut = 0.5 - round(0.5 * ($data[6] / 100), 2);
                        } else {
                            $brut = 0.5;
                        }
                    } else {
                        $brut = 0.5;
                    }
                    $brut = $brut * $data[1];
                } else {
                    $brut = $ggrPlayer['automaticMovement'] / 100;
                }

                if (!isset($users[$ggrPlayer['user_id']])) {
                    $users[$ggrPlayer['user_id']] = 0;
                }

                $users[$ggrPlayer['user_id']] = $brut + $users[$ggrPlayer['user_id']];
            }
            return $users;
        }

        return [];
    }

    /**
     * @return array
     */
    public function getAcceptingEmailsPlayers()
    {
        $acceptingEmailsPlayers = $this->reportsRepository->getAcceptingEmailsPlayers();
        $users = [];
        foreach ($acceptingEmailsPlayers as $acceptingEmailsPlayer) {
            $users[$acceptingEmailsPlayer['user_id']] = 'Y';
        }
        return $users;
    }

    /**
     * @param $userId
     *
     * @return array
     */
    public function getAutomaticAndTicketPurchaseByUserId($userId)
    {
        $userBets = $this->transactionRepository->getAutomaticAndTicketPurchaseByUserId($userId);
        return $this->generateMovement($userBets);
    }

    /**
     * @param $userId
     *
     * @return array
     */
    public function getDepositsByUserId($userId)
    {
        return $this->transactionRepository->getDepositsByUserId($userId);
    }

    /**
     * @param $userId
     *
     * @return array
     */
    public function getWithdrawalsByUserId($userId)
    {
        $userWithdrawals = $this->transactionRepository->getWithdrawalsByUserId($userId);
        foreach ($userWithdrawals as $key => $userWithdrawal) {
            $aData = explode('#', $userWithdrawal['data']);
            $userWithdrawals[$key]['amount'] = $aData[1] / 100;
            $userWithdrawals[$key]['state'] = $aData[2];
        }
        return $userWithdrawals;
    }

    /**
     * @param $userId
     *
     * @return array
     */
    public function getActivePlaysByUserId($userId)
    {
        return $this->reportsRepository->getActivePlayConfigsByUser($userId);
    }

    /**
     * @param $userId
     *
     * @return array
     */
    public function getMyActiveChristmas($userId)
    {
        if(!empty($userId)){
            return  $this->reportsRepository->getActiveChristmasByUser($userId);
        }

        return [];
    }

    /**
     * @param $userId
     * @param $nextDrawDate
     *
     * @return mixed
     */
    public function getSubscriptionsByUserIdActive($userId, $nextDrawDate, $nextDrawDatePowerBall)
    {
        $subscriptionsActives = $this->reportsRepository->getSubscriptionsByUserIdActive($userId, $nextDrawDate, $nextDrawDatePowerBall);
        foreach ($subscriptionsActives as $subscriptionsActiveKey => $subscriptionsActiveValue) {
            $subscriptionsActives[$subscriptionsActiveKey]['start_draw_date'] = (new \DateTime($subscriptionsActiveValue['start_draw_date']))->format('Y M d');
            $subscriptionsActives[$subscriptionsActiveKey]['last_draw_date'] = (new \DateTime($subscriptionsActiveValue['last_draw_date']))->format('Y M d');
            $subscriptionsActives[$subscriptionsActiveKey]['lottery'] = $subscriptionsActiveValue['lottery_id'] == 1 ? 'Euromillions' : 'PowerBall';
        }
        return $subscriptionsActives;
    }

    /**
     * @param $userId
     *
     * @return mixed
     */
    public function getSubscriptionsByUserIdInactive($userId)
    {
        $subscriptionsActives = $this->reportsRepository->getSubscriptionsByUserIdInactive($userId);
        foreach ($subscriptionsActives as $subscriptionsActiveKey =>  $subscriptionsActiveValue) {
            $subscriptionsActives[$subscriptionsActiveKey]['start_draw_date'] = (new \DateTime($subscriptionsActiveValue['start_draw_date']))->format('Y M d');
            $subscriptionsActives[$subscriptionsActiveKey]['last_draw_date'] = (new \DateTime($subscriptionsActiveValue['last_draw_date']))->format('Y M d');
            $subscriptionsActives[$subscriptionsActiveKey]['lottery'] = $subscriptionsActiveValue['lottery_id'] == 1 ? 'Euromillions' : 'PowerBall';
        }
        return $subscriptionsActives;
    }

    /**
     * @param $userId
     *
     * @return mixed
     */
    public function getPastGamesWithPrizes($userId)
    {
        return $this->reportsRepository->getPastGamesWithPrizes($userId);
    }

    /**
     * @param $id
     *
     * @return array
     */
    public function getEuromillionsDrawsActualAfterDatesById($id)
    {
        /** @var EuroMillionsDraw $actualDraw */
        $actualDraw = $this->lotteryDrawRepository->find($id);
        $nextDrawDate = clone $actualDraw->getDrawDate()->setTime(19, 30, 00);
        $actualDrawDate = $this->getNextDateDrawByLottery('Euromillions', $actualDraw->getDrawDate()->modify('-5 days'))->setTime(19, 30, 00);

        return ['actualDrawDate' => $actualDrawDate, 'nextDrawDate' => $nextDrawDate];
    }

    /**
     * @param $id
     *
     * @return array
     */
    public function getPowerBallDrawsActualAfterDatesById($id)
    {
        /** @var EuroMillionsDraw $actualDraw */
        $actualDraw = $this->lotteryDrawRepository->find($id);
        $nextDrawDate = clone $actualDraw->getDrawDate()->setTime(03, 00, 00);
        $nextDrawDate = $nextDrawDate->modify('+1 day');
        $actualDrawDate = $this->getNextDateDrawByLottery('PowerBall', $actualDraw->getDrawDate()->modify('-5 days'))->setTime(03, 00, 00);
        $actualDrawDate = $actualDrawDate->modify('+1 day');

        return ['actualDrawDate' => $actualDrawDate, 'nextDrawDate' => $nextDrawDate];
    }

    /**
     * @param $id
     *
     * @return array
     */
    public function getChristmasDrawsActualAfterDatesById($id)
    {
        /** @var EuroMillionsDraw $actualDraw */
        $actualDraw = $this->lotteryDrawRepository->find($id);
        $nextDrawDate = clone $actualDraw->getDrawDate()->setTime(19, 30, 00);
        $actualDrawDate = $actualDraw->getDrawDate()->setDate($actualDraw->getDrawDate()->format('Y'), 1, 1)->setTime(1, 0,0);

        return ['actualDrawDate' => $actualDrawDate, 'nextDrawDate' => $nextDrawDate];
    }

    /**
     * @param $date
     *
     * @return array
     */
    public function getEuromillionsDrawsActualAfterDatesByDrawDate($date)
    {
        $actualDraw = new \DateTime($date);
        $nextDrawDate = clone $actualDraw->setTime(19, 30, 00);
        $actualDrawDate = $this->getNextDateDrawByLottery('Euromillions', $actualDraw->modify('-5 days'))->setTime(19, 30, 00);

        return ['actualDrawDate' => $actualDrawDate, 'nextDrawDate' => $nextDrawDate];
    }

    /**
     * @param $date
     *
     * @return array
     */
    public function getPowerBallDrawsActualAfterDatesByDrawDate($date)
    {
        $actualDraw = new \DateTime($date);
        $nextDrawDate = clone $actualDraw->modify('+27 hours');
        $actualDrawDate = $this->getNextDateDrawByLottery('PowerBall', $actualDraw->modify('-5 days'))->setTime(03, 00, 00);
        $actualDrawDate = $actualDrawDate->modify('+1 day');

        return ['actualDrawDate' => $actualDrawDate, 'nextDrawDate' => $nextDrawDate];
    }

    public function getChristmassDrawsActualAfterDatesByDrawDate($date)
    {
        $actualDraw = new \DateTime($date);
        $nextDrawDate = clone $actualDraw->setTime(19, 30, 00);
        $actualDrawDate = $actualDraw->setDate($actualDraw->format('Y'), 1, 1)->setTime(1, 0,0);

        return ['actualDrawDate' => $actualDrawDate, 'nextDrawDate' => $nextDrawDate];
    }

    /**
     * @param $id
     * @param $drawDates
     *
     * @return array
     */
    public function getEuromillionsDrawDetailsByIdAndDates($id, $drawDates)
    {
        $drawDetails = $this->generateMovement($this->reportsRepository->getEuromillionsDrawDetailsByIdAndDates($id, $drawDates));
        foreach ($drawDetails as $drawKey => $drawValue) {
            if ($drawValue['entity_type'] == 'ticket_purchase') {
                $drawData = explode('#', $drawValue['data']);
                if (isset($drawData[5])) {
                    $betIds = explode(',', $drawData[5]);
                    $cont = 0;
                    foreach ($betIds as $betId) {
                        $drawDetails[$drawKey]['betIds']['id'][$cont] = $betId;
                        $drawDetails[$drawKey]['betIds']['numbers'][$cont] = implode(", ", $this->reportsRepository->getNumbersPlayedByBetId($betId));
                        $cont++;
                    }
                }
            } elseif ($drawValue['entity_type'] == 'automatic_purchase') {
                $drawData = explode('#', $drawValue['data']);
                if (isset($drawData[2])) {
                    $drawDetails[$drawKey]['betIds']['id'][0] = $drawData[2];
                    $drawDetails[$drawKey]['betIds']['numbers'][0] = implode(", ", $this->reportsRepository->getNumbersPlayedByBetId($drawData[2]));
                }
            }
        }

        return $drawDetails;
    }

    /**
     * @param $id
     * @param $drawDates
     *
     * @return array
     */
    public function getPowerBallDrawDetailsByIdAndDates($id, $drawDates)
    {

        $drawDetails = $this->generateMovement($this->reportsRepository->getPowerBallDrawDetailsByIdAndDates($id, $drawDates));
        foreach ($drawDetails as $drawKey => $drawValue) {
            if ($drawValue['entity_type'] == 'ticket_purchase') {
                $drawData = explode('#', $drawValue['data']);
                if (isset($drawData[5])) {
                    $betIds = explode(',', $drawData[5]);
                    $cont = 0;
                    foreach ($betIds as $betId) {
                        $drawDetails[$drawKey]['betIds']['id'][$cont] = $betId;
                        $drawDetails[$drawKey]['betIds']['numbers'][$cont] = implode(", ", $this->reportsRepository->getNumbersPlayedByBetId($betId));
                        $cont++;
                    }
                }
            } elseif ($drawValue['entity_type'] == 'automatic_purchase') {
                $drawData = explode('#', $drawValue['data']);
                if (isset($drawData[2])) {
                    $drawDetails[$drawKey]['betIds']['id'][0] = $drawData[2];
                    $drawDetails[$drawKey]['betIds']['numbers'][0] = implode(", ", $this->reportsRepository->getNumbersPlayedByBetId($drawData[2]));
                }
            }
        }

        return $drawDetails;
    }

    /**
     * @param $id
     * @param $drawDates
     *
     * @return array
     */
    public function getChristmasDrawDetailsByIdAndDates($id, $drawDates)
    {
        $drawDetails = $this->generateMovement($this->reportsRepository->getChristmasDrawDetailsByIdAndDates($id, $drawDates));
        foreach ($drawDetails as $drawKey => $drawValue) {
            if ($drawValue['entity_type'] == 'ticket_purchase') {
                $drawData = explode('#', $drawValue['data']);
                if (isset($drawData[5])) {
                    $betIds = explode(',', $drawData[5]);
                    $cont = 0;
                    foreach ($betIds as $betId) {
                        $drawDetails[$drawKey]['betIds']['id'][$cont] = $betId;
                        $numbers = $this->reportsRepository->getNumbersPlayedByBetId($betId);
                        $drawDetails[$drawKey]['betIds']['numbers'][$cont] = $numbers['line_regular_number_one'] . $numbers['line_regular_number_two'] . $numbers['line_regular_number_three'] . $numbers['line_regular_number_four'] . $numbers['line_regular_number_five'] . ' - ' . $numbers['line_lucky_number_one'];
                        $cont++;
                    }
                }
            } elseif ($drawValue['entity_type'] == 'automatic_purchase') {
                $drawData = explode('#', $drawValue['data']);
                if (isset($drawData[2])) {
                    $drawDetails[$drawKey]['betIds']['id'][0] = $drawData[2];
                    $numbers = $this->reportsRepository->getNumbersPlayedByBetId($drawData[2]);
                    $drawDetails[$drawKey]['betIds']['numbers'][0] = $numbers['line_regular_number_one'] . $numbers['line_regular_number_two'] . $numbers['line_regular_number_three'] . $numbers['line_regular_number_four'] . $numbers['line_regular_number_five'] . ' - ' . $numbers['line_lucky_number_one'];
                }
            }
        }

        return $drawDetails;
    }

    /**
     * @return mixed
     */
    public function fetchMonthlySales()
    {
        return $this->reportsRepository->getMonthlySales();
    }

    /**
     * @return mixed
     */
    public function fetchSalesDraw()
    {
        $salesDraw = $this->reportsRepository->getSalesDraw();
        foreach ($salesDraw as $keyDraw => $valueDraw) {
            $drawDates = $this->getEuromillionsDrawsActualAfterDatesByDrawDate($valueDraw['draw_date']);
            $drawData = $this->reportsRepository->getEuromillionsDrawDetailsBetweenDrawDates($drawDates);
            $salesDraw[$keyDraw]['totalBets'] = $drawData[0]['totalBets'];
            $salesDraw[$keyDraw]['grossSales'] = $drawData[0]['grossSales'];
            $salesDraw[$keyDraw]['grossMargin'] = $drawData[0]['grossMargin'];
        }
        return $salesDraw;
    }

    /**
     * @return mixed
     */
    public function fetchSalesDrawPowerBall()
    {
        $salesDraw = $this->reportsRepository->getSalesDrawPowerBall();
        foreach ($salesDraw as $keyDraw => $valueDraw) {
            $drawDates = $this->getPowerBallDrawsActualAfterDatesByDrawDate($valueDraw['draw_date']);
//            $single_bet_price = $this->lotteryService->getSingleBetPriceByLottery('PowerBall');
            $single_bet_price = new Money(200, new Currency('USD'));
            $powerball_price = new Money(100, new Currency('USD'));
            $single_bet_price_currency = $this->currencyConversionService->convert($single_bet_price, new Currency('EUR'));
            $powerball_price_currency = $this->currencyConversionService->convert($powerball_price, new Currency('EUR'));
            $drawData = $this->reportsRepository->getPowerBallDrawDetailsBetweenDrawDates($drawDates, $single_bet_price_currency->getAmount(), $powerball_price_currency->getAmount());
            $salesDraw[$keyDraw]['totalBets'] = $drawData['totalBets'];
            $salesDraw[$keyDraw]['grossSales'] = $drawData['grossSales'];
            $salesDraw[$keyDraw]['grossMargin'] = $drawData['grossMargin'];
            $salesDraw[$keyDraw]['totalPowerplay'] = $drawData['totalPowerplay'];
        }
        return $salesDraw;
    }

    /**
     * @return mixed
     */
    public function fetchSalesDrawChristmas()
    {
        $salesDraw = $this->reportsRepository->getSalesDrawChristmas();
        foreach ($salesDraw as $keyDraw => $valueDraw) {
            $drawDates = $this->getChristmassDrawsActualAfterDatesByDrawDate($valueDraw['draw_date']);
            $drawData = $this->reportsRepository->getChristmasDrawDetailsBetweenDrawDates($drawDates);
            $salesDraw[$keyDraw]['totalBets'] = $drawData[0]['totalBets'];
            $salesDraw[$keyDraw]['grossSales'] = $drawData[0]['grossSales'];
            $salesDraw[$keyDraw]['grossMargin'] = $drawData[0]['grossMargin'];
        }
        return $salesDraw;
    }

    /**
     * @return mixed
     */
    public function fetchCustomerData()
    {
        return $this->reportsRepository->getCustomersData();
    }

    /**
     * @param $lotteryName
     * @param \DateTime|null $now
     *
     * @return \DateTime
     */
    public function getNextDateDrawByLottery($lotteryName, \DateTime $now = null)
    {
        if (!$now) {
            $now = new \DateTime();
        }
        $lottery = $this->lotteryRepository->findOneBy(['name' => $lotteryName]);
        return $lottery->getNextDrawDate($now);
    }

    /**
     * @param $userId
     * @param $isChecked
     * @param $userDate
     *
     * @throws Exception
     */
    public function saveDisabledUser($userId, $isChecked, $userDate)
    {
        try {
            if ($userId) {
                /** @var User $user */
                $user = $this->userRepository->findOneBy(['id' => $userId]);

                if ($isChecked == 'true') {
                    if ($userDate) {
                        $user->setDisabledDate(new \DateTime($userDate));
                    } else {
                        $user->setDisabledDate((new \DateTime())->modify('+100 year'));
                    }
                } else {
                    $user->setDisabledDate(null);
                }

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                echo 'User was saved correctly.';
            } else {
                echo '!The disabled option was not saved!';
            }
        } catch (Exception $e) {
            throw new Exception('!The disabled option was not saved!');
        }
    }

    /**
     * @param $userBets
     *
     * @return array
     */
    private function generateMovement($userBets)
    {
        foreach ($userBets as $key => $bet) {
            if ($bet['entity_type'] == 'ticket_purchase') {
                $aData = explode('#', $bet['data']);
                if (isset($aData[6])) {
                    if ($aData[6] > 0) {
                        $userBets[$key]["movement"] = ($aData[2] - round(($aData[2] * ($aData[6] / 100)))) / 100;
                    } else {
                        $userBets[$key]["movement"] = $aData[2] / 100;
                    }
                } else {
                    $userBets[$key]["movement"] = $aData[2] / 100;
                }
            } else {
                $userBets[$key]["movement"] = $userBets[$key]['automaticMovement'] / 100;
            }
        }

        return $userBets;
    }

    /**
     * @param array $aPlayersReportsKeys
     * @param array $postData
     *
     * @return array
     */
    private function generateSQLByPlayersReports(array $aPlayersReportsKeys, array $postData)
    {
        $dateFrom = ($postData['dateFrom']) ? (new \DateTime($postData['dateFrom']))->setTime(0, 0, 0) : (new \DateTime('2016-01-01'))->setTime(0, 0, 0);
        $dateTo = ($postData['dateTo']) ? (new \DateTime($postData['dateTo']))->setTime(23, 59, 59) : (new \DateTime())->setTime(23, 59, 59);

        $selectPlayersReports = ' u.id as id,';
        $selectPlayersReports .= ' u.email as email,';
        foreach ($aPlayersReportsKeys as $playerReportKey) {
            switch ($playerReportKey) {
                case "name":
                    $selectPlayersReports .= ' u.name as name,';
                    break;
                case "surname":
                    $selectPlayersReports .= ' u.surname as surname,';
                    break;
                case "phone":
                    $selectPlayersReports .= ' u.phone_number as phone,';
                    break;
                case "country":
                    $selectPlayersReports .= ' u.country as country,';
                    break;
                case "city":
                    $selectPlayersReports .= ' u.city as city,';
                    break;
                case "street":
                    $selectPlayersReports .= ' u.street as street,';
                    break;
                case "ip":
                    $selectPlayersReports .= ' u.ip_address_value as ip,';
                    break;
                case "registrationDate":
                    $selectPlayersReports .= ' u.created as registrationDate,';
                    break;
                case "deposited":
                case "numberDeposits":
                    $selectPlayersReports .= ' SUM(CASE WHEN t.entity_type in ("deposit", "subscription_purchase") AND t.date BETWEEN "' . $dateFrom->format('Y-m-d H:i:s') . '" AND "' . $dateTo->format('Y-m-d H:i:s') . '" THEN 1 ELSE 0 END) as numberDeposits,';
                    break;
                case "amountDeposited":
                    $selectPlayersReports .= ' SUM(CASE WHEN t.entity_type = "deposit" AND t.date BETWEEN "' . $dateFrom->format('Y-m-d H:i:s') . '" AND "' . $dateTo->format('Y-m-d H:i:s') . '" THEN t.wallet_after_uploaded_amount - t.wallet_before_uploaded_amount
                                                        WHEN t.entity_type = "subscription_purchase" AND t.date BETWEEN "' . $dateFrom->format('Y-m-d H:i:s') . '" AND "' . $dateTo->format('Y-m-d H:i:s') . '" THEN t.wallet_after_subscription_amount - t.wallet_before_subscription_amount
                                                        ELSE 0 END) as amountDeposited,';
                    break;
                case "numberWithdrawals":
                    $selectPlayersReports .= ' SUM(CASE WHEN t.entity_type = "winnings_withdraw" AND t.date BETWEEN "' . $dateFrom->format('Y-m-d H:i:s') . '" AND "' . $dateTo->format('Y-m-d H:i:s') . '" THEN 1 ELSE 0 END) as numberWithdrawals, ';
                    break;
                case "amountWithdraw":
                    $selectPlayersReports .= ' SUM(CASE WHEN t.entity_type = "winnings_withdraw" AND t.date BETWEEN "' . $dateFrom->format('Y-m-d H:i:s') . '" AND "' . $dateTo->format('Y-m-d H:i:s') . '" THEN t.wallet_before_winnings_amount - t.wallet_after_winnings_amount ELSE 0 END) as amountWithdraw,';
                    break;
                case "LastDepositDate":
                    $selectPlayersReports .= ' max(CASE WHEN t.entity_type in ("deposit", "subscription_purchase") THEN t.date END) as LastDepositDate,';
                    break;
                case "LastLoginDate":
                    $selectPlayersReports .= ' u.last_connection as LastLoginDate,';
                    break;
                case "balance":
                    $selectPlayersReports .= ' (u.wallet_uploaded_amount + u.wallet_winnings_amount) as balance,';
                    break;
                case "winnings":
                    $selectPlayersReports .= ' SUM(CASE WHEN t.entity_type = "winnings_received" AND t.date BETWEEN "' . $dateFrom->format('Y-m-d H:i:s') . '" AND "' . $dateTo->format('Y-m-d H:i:s') . '" THEN t.wallet_after_winnings_amount - t.wallet_before_winnings_amount ELSE 0 END) as winnings,';
                    break;
                case "numberBets":
                    $selectPlayersReports .= ' sum(CASE
                                        WHEN entity_type = "ticket_purchase" AND t.date BETWEEN "' . $dateFrom->format('Y-m-d H:i:s') . '" AND "' . $dateTo->format('Y-m-d H:i:s') . '" THEN (SUBSTRING_INDEX(SUBSTRING_INDEX(t.data, "#", 2), "#", -1))
                                        WHEN entity_type = "automatic_purchase" AND t.date BETWEEN "' . $dateFrom->format('Y-m-d H:i:s') . '" AND "' . $dateTo->format('Y-m-d H:i:s') . '" THEN 1
                                        ELSE 0
                                        END
                                    ) as numberBets,';
                    break;
                case "wagering":
                    $selectPlayersReports .= ' sum(CASE
                                        WHEN entity_type = "ticket_purchase" AND data like "1#%" AND t.date BETWEEN "' . $dateFrom->format('Y-m-d H:i:s') . '" AND "' . $dateTo->format('Y-m-d H:i:s') . '" THEN (SUBSTRING_INDEX(SUBSTRING_INDEX(data, "#", 2), "#", -1) * 300)
                                        WHEN entity_type = "ticket_purchase" AND data like "2#%" AND t.date BETWEEN "' . $dateFrom->format('Y-m-d H:i:s') . '" AND "' . $dateTo->format('Y-m-d H:i:s') . '" THEN (SUBSTRING_INDEX(SUBSTRING_INDEX(data, "#", 2), "#", -1) * 2500) 
                                        WHEN entity_type = "automatic_purchase" AND t.date BETWEEN "' . $dateFrom->format('Y-m-d H:i:s') . '" AND "' . $dateTo->format('Y-m-d H:i:s') . '" THEN (wallet_before_subscription_amount - wallet_after_subscription_amount)
                                        ELSE 0
                                        END
                                    ) as wagering,';
                    break;
                case "ggr":
                    $selectPlayersReports .= ' SUM(CASE 
                                    WHEN entity_type = "automatic_purchase" AND t.date BETWEEN "' . $dateFrom->format('Y-m-d H:i:s') . '" AND "' . $dateTo->format('Y-m-d H:i:s') . '" THEN (wallet_before_subscription_amount - wallet_after_subscription_amount - 250)
                                    WHEN entity_type = "ticket_purchase" AND data like "1#%" AND (wallet_before_subscription_amount - wallet_after_subscription_amount) > 0 AND t.date BETWEEN "' . $dateFrom->format('Y-m-d H:i:s') . '" AND "' . $dateTo->format('Y-m-d H:i:s') . '" THEN (wallet_before_subscription_amount - wallet_after_subscription_amount - ((SUBSTRING_INDEX(SUBSTRING_INDEX(data, "#", 2), "#", -1) * 250)))
                                    WHEN entity_type = "ticket_purchase" AND data like "1#%" AND (wallet_before_subscription_amount - wallet_after_subscription_amount) <= 0 AND t.date BETWEEN "' . $dateFrom->format('Y-m-d H:i:s') . '" AND "' . $dateTo->format('Y-m-d H:i:s') . '" THEN (SUBSTRING_INDEX(SUBSTRING_INDEX(data, "#", 2), "#", -1) * 50)
                                    WHEN entity_type = "ticket_purchase" AND data like "2#%" AND t.date BETWEEN "' . $dateFrom->format('Y-m-d H:i:s') . '" AND "' . $dateTo->format('Y-m-d H:i:s') . '" THEN (SUBSTRING_INDEX(SUBSTRING_INDEX(data, "#", 2), "#", -1) * 500)
                                END) as ggr,';
                    break;
                case "bonusCost":
                    //de momento no se hace nada
                    break;
                case "acceptingEmails":
                    //Pasamos array con accepting emails de todos los usuarios a la vista
                    break;
            }
        }

        $whereConditions = '';
        $havingConditions = '';
        if ($postData['user']) {
            $whereConditions .= ' u.id = "' . $postData['user'] . '" AND ';
        }

        if ($postData['name']) {
            $whereConditions .= ' u.name like "%' . $postData['name'] . '%" AND ';
        }

        if ($postData['surname']) {
            $whereConditions .= ' u.surname like "%' . $postData['surname'] . '%" AND ';
        }

        if ($postData['email']) {
            $whereConditions .= ' u.email = "' . $postData['email'] . '" AND ';
        }

        if ($postData['countries']) {
            $whereConditions .= ' u.country IN (\'' . implode("','", $postData['countries']) . '\') AND ';
        }

        if ($postData['trackingCode'] != 0) {
            $whereConditions .= 'u.id IN (select user_id from tc_users_list where trackingCode_id IN ("' . $postData['trackingCode'] . '")) AND ';
        }

        if ($postData['depositor']) {
            $selectPlayersReports .= ' SUM(CASE WHEN t.entity_type in ("deposit", "subscription_purchase") AND t.date BETWEEN "' . $dateFrom->format('Y-m-d H:i:s') . '" AND "' . $dateTo->format('Y-m-d H:i:s') . '" THEN 1 ELSE 0 END) as numberDeposits,';
            if ($postData['depositor'] == 'Y') {
                $havingConditions = ' HAVING numberDeposits > 0';
            } else {
                $havingConditions = ' HAVING numberDeposits = 0';
            }
        }

        if ($whereConditions != '') {
            $whereConditions = ' WHERE ' . substr($whereConditions, 0, -4);
        }

        $sql = 'SELECT ' . substr($selectPlayersReports, 0, -1) . '
                FROM users u
                LEFT JOIN transactions t ON t.user_id = u.id
                ' . $whereConditions . '
                GROUP BY u.id
                ' . $havingConditions;

        return $this->reportsRepository->getUsersByReportsPlayersQuery($sql);
    }


    public function getActivities($data)
    {
        $arrayResults = [];
        $arrayResultsMonths = [];
        $arrayTotals = [];
        $total = [];
        $controlActives = [];
        $countActives = 0;
        $anotherCountActives = [];
        $controlDepositors = [];
        $controlReactivated = [];
        $controlInactives = [];
        //Ordenar por fecha, y dentro por pais, y meter todas las columnas
        $newRegistrations = $this->reportsRepository->getNewRegistrations($data);
        $depositors0 = $this->reportsRepository->getDepositorsD0($data);
        $depositors1 = $this->reportsRepository->getDepositorsD1($data);
        $depositors2 = $this->reportsRepository->getDepositorsD2($data);
        $actives = $this->reportsRepository->getActives($data);
        $justInactives = $this->reportsRepository->getJustInactives($data);
        $inactives = $this->reportsRepository->getInactives($data);
        $dormant = $this->reportsRepository->getDormant($data);
        $reactivatedJI = $this->reportsRepository->getReactivatedJI($data);
        $reactivatedIN = $this->reportsRepository->getReactivatedIN($data);
        $reactivatedDOR = $this->reportsRepository->getReactivatedDOR($data);
        $activesBeginning = $this->reportsRepository->getActivesBeginning($data);
        $activesEnd = $this->reportsRepository->getActivesEnd($data);
        $churnRate = (($activesBeginning[0]['id'] - $activesEnd[0]['id']) / $activesBeginning[0]['id'] * 100);

        if ($data['groupBy'] == 'day') {
            $order = 2;
        } elseif ($data['groupBy'] == 'month') {
            $order = 1;
        } else {
            $order = 0;
        }

        foreach ($newRegistrations as $new) {
            $date = explode('-', $new['created']);
            if ($order == 2) {
                $date[$order] = $date[$order] . " - " . $date[$order - 1];
            }

            if (!in_array($date[$order], $arrayResultsMonths)) {
                $arrayResultsMonths[] = $date[$order];
                $arrayTotals[] = $date[$order];
            }

            $arrayResults[$date[$order]][$new['country']]['newRegistrations'] += (int)[$new['id']];
            $arrayTotals[$date[$order]]['newRegistrations'] += (int)[$new['id']];
            $total['newRegistrations'] += (int)[$new['id']];
        }


        foreach ($depositors0 as $new) {

            $date = explode('-', $new['created']);
            if ($order == 2) {
                $date[$order] = $date[$order] . " - " . $date[$order - 1];
            }

            if (!in_array($date[$order], $arrayResultsMonths)) {
                $arrayResultsMonths[] = $date[$order];
                $arrayTotals[] = $date[$order];
            }

            if (!in_array($new['id'], $controlDepositors[$date[$order]])) {
                $controlDepositors[$date[$order]][] = $new['id'];
                $arrayResults[$date[$order]][$new['country']]['depositorsD0'] += 1;
                $arrayTotals[$date[$order]]['depositorsD0'] += 1;
                $total['depositorsD0'] += 1;
            }
        }
        
        foreach ($depositors1 as $new) {

            $date = explode('-', $new['created']);
            if ($order == 2) {
                $date[$order] = $date[$order] . " - " . $date[$order - 1];
            }

            if (!in_array($date[$order], $arrayResultsMonths)) {
                $arrayResultsMonths[] = $date[$order];
                $arrayTotals[] = $date[$order];
            }

            if (!in_array($new['id'], $controlDepositors[$date[$order]])) {
                $controlDepositors[$date[$order]][] = $new['id'];
                $arrayResults[$date[$order]][$new['country']]['depositorsD1'] += 1;
                $arrayTotals[$date[$order]]['depositorsD1'] += 1;
                $total['depositorsD1'] += 1;
            }
        }
        
        foreach ($depositors2 as $new) {

            $date = explode('-', $new['created']);
            if ($order == 2) {
                $date[$order] = $date[$order] . " - " . $date[$order - 1];
            }

            if (!in_array($date[$order], $arrayResultsMonths)) {
                $arrayResultsMonths[] = $date[$order];
                $arrayTotals[] = $date[$order];
            }

            if (!in_array($new['id'], $controlDepositors[$date[$order]])) {
                $controlDepositors[$date[$order]][] = $new['id'];
                $arrayResults[$date[$order]][$new['country']]['depositorsD2'] += 1;
                $arrayTotals[$date[$order]]['depositorsD2'] += 1;
                $total['depositorsD2'] += 1;
            }
        }

        foreach ($actives as $new) {

            if (!in_array($new['id'], $anotherCountActives)) {
                $anotherCountActives[] = $new['id'];
                $countActives++;
            }

            $date = explode('-', $new['created']);
            if ($order == 2) {
                $date[$order] = $date[$order] . " - " . $date[$order - 1];
            }

            if (!in_array($date[$order], $arrayResultsMonths)) {
                $arrayResultsMonths[] = $date[$order];
                $arrayTotals[] = $date[$order];
            }

            if (!in_array($new['id'], $controlActives[$date[$order]])) {
                $controlActives[$date[$order]][] = $new['id'];
                $arrayResults[$date[$order]][$new['country']]['actives'] += 1;
                $arrayTotals[$date[$order]]['actives'] += 1;
                $total['actives'] += 1;
            }

        }
        
        foreach ($justInactives as $new) {
            $date = explode('-', date('Y-F-d', strtotime($data['dateTo'])));
            if ($order == 2) {
                $date[$order] = $date[$order] . " - " . $date[$order - 1];
            }

            if (!in_array($date[$order], $arrayResultsMonths)) {
                $arrayResultsMonths[] = $date[$order];
                $arrayTotals[] = $date[$order];
            }

            if (!in_array($new['id'], $controlInactives[$date[$order]])) {
                $controlInactives[$date[$order]][] = $new['id'];
                $arrayResults[$date[$order]][$new['country']]['justInactive'] += 1;
                $arrayTotals[$date[$order]]['justInactive'] += 1;
                $total['justInactive'] += 1;
            }
        }

        foreach ($inactives as $new) {
            $date = explode('-', date('Y-F-d', strtotime($data['dateTo'])));
            if ($order == 2) {
                $date[$order] = $date[$order] . " - " . $date[$order - 1];
            }

            if (!in_array($date[$order], $arrayResultsMonths)) {
                $arrayResultsMonths[] = $date[$order];
                $arrayTotals[] = $date[$order];
            }

            if (!in_array($new['id'], $controlInactives[$date[$order]])) {
                $controlInactives[$date[$order]][] = $new['id'];
                $arrayResults[$date[$order]][$new['country']]['inactive'] += 1;
                $arrayTotals[$date[$order]]['inactive'] += 1;
                $total['inactive'] += 1;
            }
        }

        foreach ($dormant as $new) {
            $date = explode('-', date('Y-F-d', strtotime($data['dateTo'])));
            if ($order == 2) {
                $date[$order] = $date[$order] . " - " . $date[$order - 1];
            }

            if (!in_array($date[$order], $arrayResultsMonths)) {
                $arrayResultsMonths[] = $date[$order];
                $arrayTotals[] = $date[$order];
            }

            if (!in_array($new['id'], $controlInactives[$date[$order]])) {
                $controlInactives[$date[$order]][] = $new['id'];
                $arrayResults[$date[$order]][$new['country']]['dormant'] += 1;
                $arrayTotals[$date[$order]]['dormant'] += 1;
                $total['dormant'] += 1;
            }
        }

        foreach ($reactivatedJI as $new) {
            $date = explode('-', date('Y-F-d', strtotime($data['dateTo'])));
            if ($order == 2) {
                $date[$order] = $date[$order] . " - " . $date[$order - 1];
            }

            if (!in_array($date[$order], $arrayResultsMonths)) {
                $arrayResultsMonths[] = $date[$order];
                $arrayTotals[] = $date[$order];
            }

            if (!in_array($new['id'], $controlReactivated[$date[$order]])) {
                $controlReactivated[$date[$order]][] = $new['id'];
                $arrayResults[$date[$order]][$new['country']]['reactivatedJI'] += 1;
                $arrayTotals[$date[$order]]['reactivatedJI'] += 1;
                $total['reactivatedJI'] += 1;
            }
        }

        foreach ($reactivatedIN as $new) {
            $date = explode('-', date('Y-F-d', strtotime($data['dateTo'])));
            if ($order == 2) {
                $date[$order] = $date[$order] . " - " . $date[$order - 1];
            }

            if (!in_array($date[$order], $arrayResultsMonths)) {
                $arrayResultsMonths[] = $date[$order];
                $arrayTotals[] = $date[$order];
            }

            if (!in_array($new['id'], $controlReactivated[$date[$order]])) {
                $controlReactivated[$date[$order]][] = $new['id'];
                $arrayResults[$date[$order]][$new['country']]['reactivatedIN'] += 1;
                $arrayTotals[$date[$order]]['reactivatedIN'] += 1;
                $total['reactivatedIN'] += 1;
            }
        }

        foreach ($reactivatedDOR as $new) {
            $date = explode('-', date('Y-F-d', strtotime($data['dateTo'])));
            if ($order == 2) {
                $date[$order] = $date[$order] . " - " . $date[$order - 1];
            }

            if (!in_array($date[$order], $arrayResultsMonths)) {
                $arrayResultsMonths[] = $date[$order];
                $arrayTotals[] = $date[$order];
            }

            if (!in_array($new['id'], $controlReactivated[$date[$order]])) {
                $controlReactivated[$date[$order]][] = $new['id'];
                $arrayResults[$date[$order]][$new['country']]['reactivatedDOR'] += 1;
                $arrayTotals[$date[$order]]['reactivatedDOR'] += 1;
                $total['reactivatedDOR'] += 1;
            }
        }

        return [$arrayResults, $arrayResultsMonths, $arrayTotals, $total, $countActives];
    }

    public function getGeneralKPI($data)
    {
        $arrayResults = [];
        $arrayResultsMonths = [];
        $arrayTotals = [];
        $total = [];
        $controlActives = [];
        $countActives = 0;
        $anotherCountActives = [];
        $controlDepositors = [];
        //Ordenar por fecha, y dentro por pais, y meter todas las columnas
        $newRegistrations = $this->reportsRepository->getNewRegistrations($data);
        $newDepositors = $this->reportsRepository->getNewDepositors($data);
        $conversion = ($newDepositors['0']['id'] / $newRegistrations['0']['id'] * 100);

        $actives = $this->reportsRepository->getActives($data);

        $numberBets = $this->reportsRepository->getNumberBets($data);
        $totalBets = $this->reportsRepository->getTotalBetsAmount($data);
        $numberDeposits = $this->reportsRepository->getNumberDeposits($data);
        $depositAmount = $this->reportsRepository->getDepositAmount($data);
        $numberWithdrawals = $this->reportsRepository->getNumberWithdrawals($data);
        $withdrawalAmount = $this->reportsRepository->getWithdrawalAmount($data);
        $grossGaming = $this->reportsRepository->getGrossGamingRevenue($data);
        $playerWinnings = $this->reportsRepository->getPlayerWinnings($data);

        if ($data['groupBy'] == 'day') {
            $order = 2;
        } elseif ($data['groupBy'] == 'month') {
            $order = 1;
        } else {
            $order = 0;
        }

        foreach ($newRegistrations as $new) {
            $date = explode('-', $new['created']);
            if ($order == 2) {
                $date[$order] = $date[$order] . " - " . $date[$order - 1];
            }

            if (!in_array($date[$order], $arrayResultsMonths)) {
                $arrayResultsMonths[] = $date[$order];
                $arrayTotals[] = $date[$order];
            }

            $arrayResults[$date[$order]][$new['country']]['newRegistrations'] += (int)[$new['id']];
            $arrayTotals[$date[$order]]['newRegistrations'] += (int)[$new['id']];
            $total['newRegistrations'] += (int)[$new['id']];
        }

        foreach ($newDepositors as $new) {
            $date = explode('-', $new['created']);
            if ($order == 2) {
                $date[$order] = $date[$order] . " - " . $date[$order - 1];
            }

            if (!in_array($date[$order], $arrayResultsMonths)) {
                $arrayResultsMonths[] = $date[$order];
                $arrayTotals[] = $date[$order];
            }

            if (!in_array($new['id'], $controlDepositors[$date[$order]])) {
                $controlDepositors[$date[$order]][] = $new['id'];
                $arrayResults[$date[$order]][$new['country']]['newDepositors'] += 1;
                $arrayTotals[$date[$order]]['newDepositors'] += 1;
                $total['newDepositors'] += 1;
            }
        }

        foreach ($actives as $new) {

            if (!in_array($new['id'], $anotherCountActives)) {
                $anotherCountActives[] = $new['id'];
                $countActives++;
            }

            $date = explode('-', $new['created']);
            if ($order == 2) {
                $date[$order] = $date[$order] . " - " . $date[$order - 1];
            }

            if (!in_array($date[$order], $arrayResultsMonths)) {
                $arrayResultsMonths[] = $date[$order];
                $arrayTotals[] = $date[$order];
            }

            if (!in_array($new['id'], $controlActives[$date[$order]])) {
                $controlActives[$date[$order]][] = $new['id'];
                $arrayResults[$date[$order]][$new['country']]['actives'] += 1;
                $arrayTotals[$date[$order]]['actives'] += 1;
                $total['actives'] += 1;
            }

        }

        foreach ($numberBets as $new) {
            $date = explode('-', $new['created']);
            if ($order == 2) {
                $date[$order] = $date[$order] . " - " . $date[$order - 1];
            }

            if (!in_array($date[$order], $arrayResultsMonths)) {
                $arrayResultsMonths[] = $date[$order];
                $arrayTotals[] = $date[$order];
            }

            $arrayResults[$date[$order]][$new['country']]['numberBets'] += (int)$new['id'];
            $arrayTotals[$date[$order]]['numberBets'] += (int)$new['id'];
            $total['numberBets'] += (int)$new['id'];
        }

        foreach ($totalBets as $new) {
            $date = explode('-', $new['created']);
            if ($order == 2) {
                $date[$order] = $date[$order] . " - " . $date[$order - 1];
            }

            if (!in_array($date[$order], $arrayResultsMonths)) {
                $arrayResultsMonths[] = $date[$order];
                $arrayTotals[] = $date[$order];
            }

            $arrayResults[$date[$order]][$new['country']]['totalBets'] += (int)$new['id'];
            $arrayTotals[$date[$order]]['totalBets'] += (int)$new['id'];
            $total['totalBets'] += (int)$new['id'];
        }

        foreach ($numberDeposits as $new) {
            $date = explode('-', $new['created']);
            if ($order == 2) {
                $date[$order] = $date[$order] . " - " . $date[$order - 1];
            }

            if (!in_array($date[$order], $arrayResultsMonths)) {
                $arrayResultsMonths[] = $date[$order];
                $arrayTotals[] = $date[$order];
            }

            $arrayResults[$date[$order]][$new['country']]['numberDeposits'] += (int)$new['id'];
            $arrayTotals[$date[$order]]['numberDeposits'] += (int)$new['id'];
            $total['numberDeposits'] += (int)$new['id'];
        }

        foreach ($depositAmount as $new) {
            $date = explode('-', $new['created']);
            if ($order == 2) {
                $date[$order] = $date[$order] . " - " . $date[$order - 1];
            }

            if (!in_array($date[$order], $arrayResultsMonths)) {
                $arrayResultsMonths[] = $date[$order];
                $arrayTotals[] = $date[$order];
            }

            $arrayResults[$date[$order]][$new['country']]['depositAmount'] += (int)$new['id'];
            $arrayTotals[$date[$order]]['depositAmount'] += (int)$new['id'];
            $total['depositAmount'] += (int)$new['id'];
        }

        foreach ($numberWithdrawals as $new) {
            $date = explode('-', $new['created']);
            if ($order == 2) {
                $date[$order] = $date[$order] . " - " . $date[$order - 1];
            }

            if (!in_array($date[$order], $arrayResultsMonths)) {
                $arrayResultsMonths[] = $date[$order];
                $arrayTotals[] = $date[$order];
            }

            $arrayResults[$date[$order]][$new['country']]['numberWithdrawals'] += (int)$new['id'];
            $arrayTotals[$date[$order]]['numberWithdrawals'] += (int)$new['id'];
            $total['numberWithdrawals'] += (int)$new['id'];
        }

        foreach ($withdrawalAmount as $new) {
            $date = explode('-', $new['created']);
            if ($order == 2) {
                $date[$order] = $date[$order] . " - " . $date[$order - 1];
            }

            if (!in_array($date[$order], $arrayResultsMonths)) {
                $arrayResultsMonths[] = $date[$order];
                $arrayTotals[] = $date[$order];
            }

            $arrayResults[$date[$order]][$new['country']]['withdrawalAmount'] += (int)$new['id'];
            $arrayTotals[$date[$order]]['withdrawalAmount'] += (int)$new['id'];
            $total['withdrawalAmount'] += (int)$new['id'];
        }

        foreach ($playerWinnings as $new) {
            $date = explode('-', $new['created']);
            if ($order == 2) {
                $date[$order] = $date[$order] . " - " . $date[$order - 1];
            }

            if (!in_array($date[$order], $arrayResultsMonths)) {
                $arrayResultsMonths[] = $date[$order];
                $arrayTotals[] = $date[$order];
            }

            $arrayResults[$date[$order]][$new['country']]['playerWinnings'] += (int)$new['id'];
            $arrayTotals[$date[$order]]['playerWinnings'] += (int)$new['id'];
            $total['playerWinnings'] += (int)$new['id'];
        }

        foreach ($grossGaming as $new) {
            $date = explode('-', $new['created']);
            if ($order == 2) {
                $date[$order] = $date[$order] . " - " . $date[$order - 1];
            }

            if (!in_array($date[$order], $arrayResultsMonths)) {
                $arrayResultsMonths[] = $date[$order];
                $arrayTotals[] = $date[$order];
            }

            $arrayResults[$date[$order]][$new['country']]['grossGaming'] += (int)$new['id'];
            $arrayTotals[$date[$order]]['grossGaming'] += (int)$new['id'];
            $total['grossGaming'] += (int)$new['id'];
        }

        return [$arrayResults, $arrayResultsMonths, $arrayTotals, $total, $countActives];
    }
}
