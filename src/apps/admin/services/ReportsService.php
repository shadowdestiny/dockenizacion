<?php


namespace EuroMillions\admin\services;

use Doctrine\ORM\EntityManager;
use EuroMillions\web\entities\User;
use EuroMillions\web\interfaces\IReports;
use EuroMillions\web\repositories\TransactionRepository;
use EuroMillions\web\repositories\UserRepository;

class ReportsService
{
    private $entityManager;
    /** @var IReports $reportsRepository */
    private $reportsRepository;
    /** @var UserRepository $userRepository */
    private $userRepository;
    /** @var TransactionRepository $transactionRepository */
    private $transactionRepository;

    public function __construct(IReports $iReports = null, EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->reportsRepository = $iReports;
        $this->userRepository =  $this->entityManager->getRepository('EuroMillions\web\entities\User');
        $this->lotteryRepository = $this->entityManager->getRepository('EuroMillions\web\entities\Lottery');
        $this->transactionRepository = $this->entityManager->getRepository('EuroMillions\web\entities\Transaction');
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
        if ($needGGR && $dateFrom && $dateTo) {
            $dateFrom = (new \DateTime($dateFrom))->setTime(0,0,0);
            $dateTo = (new \DateTime($dateTo))->setTime(23,59,59);

            $ggrPlayers = $this->reportsRepository->getUserAndDataFromTransactionsBetweenDates(
                $dateFrom->format('Y-m-d H:i:s'),
                $dateTo->format('Y-m-d H:i:s')
            );

            $users = [];
            foreach ($ggrPlayers as $ggrPlayer) {
                $data = explode('#', $ggrPlayer['data']);
                $discount = substr(strrchr($ggrPlayer['data'], '#'),1);

                if ($discount > 0) {
                    $brut = 0.5 - (0.5 * ($discount/100));
                } else {
                    $brut = 0.5;
                }
                $brut = $brut * $data[1];

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
     * @param $userId
     *
     * @return array
     */
    public function getAutomaticAndTicketPurchaseByUserId($userId)
    {
        $userBets = $this->transactionRepository->getAutomaticAndTicketPurchaseByUserId($userId);
        foreach($userBets as $key => $bet){
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
        foreach ($userWithdrawals as $key => $userWithdrawal){
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
     * @return mixed
     */
    public function getPastGamesWithPrizes($userId)
    {
        return $this->reportsRepository->getPastGamesWithPrizes($userId);
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
        return $this->reportsRepository->getSalesDraw();
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
     * @param array $aPlayersReportsKeys
     * @param array $postData
     *
     * @return array
     */
    private function generateSQLByPlayersReports(array $aPlayersReportsKeys, array $postData)
    {
        $dateFrom = ($postData['dateFrom']) ? (new \DateTime($postData['dateFrom']))->setTime(0,0,0) : (new \DateTime('2016-01-01'))->setTime(0,0,0);
        $dateTo = ($postData['dateTo']) ? (new \DateTime($postData['dateTo']))->setTime(23,59,59) : (new \DateTime('2026-01-01'))->setTime(23,59,59);

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
                    $selectPlayersReports .= ' SUM(CASE WHEN t.entity_type = "deposit" AND t.date BETWEEN "' . $dateFrom->format('Y-m-d H:i:s') . '" AND "' . $dateTo->format('Y-m-d H:i:s') . '" THEN 1 ELSE 0 END) as numberDeposits,';
                    break;
                case "amountDeposited":
                    $selectPlayersReports .= ' SUM(CASE WHEN t.entity_type = "deposit" AND t.date BETWEEN "' . $dateFrom->format('Y-m-d H:i:s') . '" AND "' . $dateTo->format('Y-m-d H:i:s') . '" THEN t.wallet_after_uploaded_amount - t.wallet_before_uploaded_amount ELSE 0 END) as amountDeposited,';
                    break;
                case "numberWithdrawals":
                    $selectPlayersReports .= ' SUM(CASE WHEN t.entity_type = "winnings_withdraw" AND t.date BETWEEN "' . $dateFrom->format('Y-m-d H:i:s') . '" AND "' . $dateTo->format('Y-m-d H:i:s') . '" THEN 1 ELSE 0 END) as numberWithdrawals, ';
                    break;
                case "amountWithdraw":
                    $selectPlayersReports .= ' SUM(CASE WHEN t.entity_type = "winnings_withdraw" AND t.date BETWEEN "' . $dateFrom->format('Y-m-d H:i:s') . '" AND "' . $dateTo->format('Y-m-d H:i:s') . '" THEN t.wallet_before_winnings_amount - t.wallet_after_winnings_amount ELSE 0 END) as amountWithdraw,';
                    break;
                case "LastDepositDate":
                    $selectPlayersReports .= ' max(CASE WHEN t.entity_type = "deposit" THEN t.date END) as LastDepositDate,';
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
                case "ggr":
                    //Pasamos un array con el ggr de todos los usuarios a la vista
                    //$selectPlayersReports .= ' SUM(CASE WHEN (t.entity_type = "ticket_purchase" || t.entity_type = "automatic_purchase") and t.date BETWEEN "' . $dateFrom->format('Y-m-d H:i:s') . '" AND "' . $dateTo->format('Y-m-d H:i:s') . '" THEN substr(t.data, 3, 1) * 0.50 ELSE 0 END)';
                    break;
                case "bonusCost":
                    //de momento no se hace nada
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
            $whereConditions .= 'u.id IN (select user_id from tc_users_list where trackingCode_id IN ("'.$postData['trackingCode'].'")) AND ';
        }

        if ($postData['depositor']) {
            $selectPlayersReports .= ' SUM(CASE WHEN t.entity_type = "deposit" AND t.date BETWEEN "' . $dateFrom->format('Y-m-d H:i:s') . '" AND "' . $dateTo->format('Y-m-d H:i:s') . '" THEN 1 ELSE 0 END) as numberDeposits,';
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
}
