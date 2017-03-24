<?php

namespace EuroMillions\web\repositories;

use Doctrine\ORM\EntityManager;
use EuroMillions\web\entities\Bet;
use EuroMillions\web\interfaces\IReports;
use Doctrine\ORM\Query\ResultSetMapping;

class ReportsRepository implements IReports
{

    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function getMonthlySales()
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('month', 'month');
        $rsm->addScalarResult('total_bets', 'total_bets');
        $rsm->addScalarResult('gross_sales', 'gross_sales');
        $rsm->addScalarResult('gross_margin', 'gross_margin');
        $rsm->addScalarResult('winnings', 'winnings');
        $result = $this->entityManager
            ->createNativeQuery(
                "SELECT MONTHNAME(d.draw_date) as month,count(b.id) as total_bets, count(b.id) * 3.00 as gross_sales, count(1) * 0.50 as gross_margin,
                (select SUM(t.wallet_after_winnings_amount)
                FROM transactions t
                WHERE MONTHNAME(t.date)=MONTHNAME(d.draw_date)
                    and entity_type='winnings_received') / 100 as winnings
                FROM bets b
                JOIN euromillions_draws d on d.id=b.euromillions_draw_id
                JOIN play_configs p on p.id=b.playConfig_id
                GROUP BY MONTH(d.draw_date)", $rsm)
            ->getResult();
        return $result;

    }

    public function getSalesDraw()
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('em', 'em');
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('draw_date', 'draw_date');
        $rsm->addScalarResult('draw_status', 'draw_status');
//        $rsm->addScalarResult('count_id', 'count_id');
//        $rsm->addScalarResult('count_id_3', 'count_id_3');
//        $rsm->addScalarResult('count_id_05', 'count_id_05');

//        select 'EM' as em, e.id as id, e.draw_date as draw_date, IF(e.draw_date < now(),'Finished','Open') as draw_status, count(b.id) as count_id, count(b.id) * 3.00 as count_id_3, count(b.id) * 0.50 as count_id_05
        return $this->entityManager
            ->createNativeQuery(
                "select 'EM' as em, e.id as id, e.draw_date as draw_date, IF(e.draw_date < now(),'Finished','Open') as draw_status
                  from euromillions_draws e
                  JOIN bets b on b.euromillions_draw_id=e.id
                  join log_validation_api l on l.bet_id=b.id
                  GROUP BY e.draw_date", $rsm)
            ->getResult();
    }


    public function getCustomersData()
    {
        $rsm = new ResultSetMapping();

        $rsm->addScalarResult('name', 'name');
        $rsm->addScalarResult('surname', 'surname');
        $rsm->addScalarResult('email', 'email');
        $rsm->addScalarResult('created', 'created');
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('currency', 'currency');
        $rsm->addScalarResult('country', 'country');
        $rsm->addScalarResult('money_deposited', 'money_deposited');
        $rsm->addScalarResult('moeny_spent', 'moeny_spent');
        $rsm->addScalarResult('winnings', 'winnings');
        $rsm->addScalarResult('balance', 'balance');
        $rsm->addScalarResult('num_bets', 'num_bets');

        return $this->entityManager
            ->createNativeQuery(
                "select u.name as name,u.surname as surname,u.email as email, u.created as created ,u.id as id ,u.user_currency_name as currency,u.country as country, (select SUM(t.wallet_after_uploaded_amount)
                  from transactions t where t.user_id=u.id and entity_type='deposit' ) / 100 as money_deposited,
                  (select SUM(t.wallet_before_uploaded_amount - t.wallet_after_uploaded_amount) from transactions t where t.user_id=u.id and entity_type='ticket_purchase') as money_spent,
                  (select SUM(t.wallet_after_winnings_amount) from transactions t where t.user_id=u.id and entity_type='winnings_received')as winnings,
                  (select us.wallet_uploaded_amount + us.wallet_winnings_amount from users us where us.id=u.id) / 100 as balance,
                  (select count(1) from bets b join play_configs p on p.id=b.playConfig_id where p.user_id=u.id) as num_bets
                  from users u;", $rsm)
            ->getResult();

    }

    public function getUsersByReportsPlayersQuery($sql)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('name', 'name');
        $rsm->addScalarResult('surname', 'surname');
        $rsm->addScalarResult('email', 'email');
        $rsm->addScalarResult('phone', 'phone');
        $rsm->addScalarResult('country', 'country');
        $rsm->addScalarResult('city', 'city');
        $rsm->addScalarResult('street', 'street');
        $rsm->addScalarResult('ip', 'ip');
        $rsm->addScalarResult('LastLoginDate', 'LastLoginDate');
        $rsm->addScalarResult('registrationDate', 'registrationDate');
        $rsm->addScalarResult('numberDeposits', 'numberDeposits');
        $rsm->addScalarResult('amountDeposited', 'amountDeposited');
        $rsm->addScalarResult('LastDepositDate', 'LastDepositDate');
        $rsm->addScalarResult('numberWithdrawals', 'numberWithdrawals');
        $rsm->addScalarResult('amountWithdraw', 'amountWithdraw');
        $rsm->addScalarResult('winnings', 'winnings');
        $rsm->addScalarResult('balance', 'balance');
        $rsm->addScalarResult('numberBets', 'numberBets');
        $rsm->addScalarResult('wagering', 'wagering');
        $rsm->addScalarResult('ggr', 'ggr');
        return $this->entityManager
            ->createNativeQuery($sql, $rsm)->getResult();
    }

    public function getUserAndDataFromTransactionsBetweenDates($dateFrom, $dateTo)
    {

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('user_id', 'user_id');
        $rsm->addScalarResult('data', 'data');
        $rsm->addScalarResult('entity_type', 'entity_type');
        $rsm->addScalarResult('automaticMovement', 'automaticMovement');
        return $this->entityManager
            ->createNativeQuery('SELECT user_id, data, entity_type, (wallet_before_subscription_amount - wallet_after_subscription_amount - 250) as automaticMovement
                                FROM transactions
                                WHERE entity_type in ("ticket_purchase", "automatic_purchase") AND 
                                date BETWEEN "' . $dateFrom . '" AND "' . $dateTo . '"',
                $rsm)->getResult();
    }

    /**
     * @param $userId
     *
     * @return array
     */
    public function getActivePlayConfigsByUser($userId)
    {
        return $this->getPlayConfigsByUserAndActive($userId, '1');
    }

    /**
     * @param $userId
     * @param $active
     *
     * @return array
     */
    protected function getPlayConfigsByUserAndActive($userId, $active)
    {
        $result = $this->entityManager
            ->createQuery(
                'SELECT b'
                . ' FROM ' . '\EuroMillions\web\entities\Bet' . ' b INNER JOIN b.playConfig p '
                . ' WHERE p.user = :user_id AND p.active = :active and p.frequency = 1 '
                . ' GROUP BY p.startDrawDate,p.line.regular_number_one,'
                . ' p.line.regular_number_two,p.line.regular_number_three, '
                . ' p.line.regular_number_four,p.line.regular_number_five, '
                . ' p.line.lucky_number_one, p.line.lucky_number_two '
                . ' ORDER BY p.startDrawDate DESC ')
            ->setParameters(['user_id' => $userId, 'active' => $active])
            ->getResult();

        $playConfigs = [];
        /** @var Bet $bet */
        foreach ($result as $bet) {
            $playConfigs[] = $bet->getPlayConfig();
        }

        return $playConfigs;

//        $receivedDate = clone $nextDrawDate;
//        if ($receivedDate->format('N') == 5) {
//            $receivedDate->modify('-3 days');
//        } else {
//            $receivedDate->modify('-4 days');
//        }
//        $receivedDate->setTime(19,30,00);
//
//        $rsm = new ResultSetMapping();
//        $rsm->addScalarResult('line_regular_number_one', 'line_regular_number_one');
//        $rsm->addScalarResult('line_regular_number_two', 'line_regular_number_two');
//        $rsm->addScalarResult('line_regular_number_three', 'line_regular_number_three');
//        $rsm->addScalarResult('line_regular_number_four', 'line_regular_number_four');
//        $rsm->addScalarResult('line_regular_number_five', 'line_regular_number_five');
//        $rsm->addScalarResult('line_lucky_number_one', 'line_lucky_number_one');
//        $rsm->addScalarResult('line_lucky_number_two', 'line_lucky_number_two');
//
//        return $this->entityManager
//            ->createNativeQuery(
//                'SELECT p.line_regular_number_one,
//                            p.line_regular_number_two,
//                            p.line_regular_number_three,
//                            p.line_regular_number_four,
//                            p.line_regular_number_five,
//                            p.line_lucky_number_one,
//                            p.line_lucky_number_two'
//                . ' FROM bets b INNER JOIN play_configs p on b.playConfig_id = p.id  '
//                . ' INNER JOIN log_validation_api lva ON lva.bet_id = b.id '
//                . ' WHERE p.user_id = "' . $userId . '" AND p.active = ' . $active . ' '
//                . ' AND last_draw_date >= "' . $nextDrawDate->format('Y-m-d') . '" AND received >= "' . $receivedDate->format('Y-m-d H:i:s') . '"
//                    GROUP BY p.start_draw_date,
//                            p.line_regular_number_one,
//                            p.line_regular_number_two,
//                            p.line_regular_number_three,
//                            p.line_regular_number_four,
//                            p.line_regular_number_five,
//                            p.line_lucky_number_one,
//                            p.line_lucky_number_two
//                    ORDER BY p.start_draw_date DESC '
//                , $rsm)->getResult();
    }

    public function getPastGamesWithPrizes($userId)
    {
        $result = $this->entityManager
            ->createQuery(
                'SELECT b,p.startDrawDate,p.line.regular_number_one,'
                . ' p.line.regular_number_two,p.line.regular_number_three, '
                . ' p.line.regular_number_four,p.line.regular_number_five, '
                . ' p.line.lucky_number_one, p.line.lucky_number_two'
                . ' FROM ' . '\EuroMillions\web\entities\Bet' . ' b JOIN b.playConfig p JOIN b.euromillionsDraw e'
                . ' WHERE p.user = :user_id AND e.draw_date < :actual_date and p.frequency = 1 '
                . ' ORDER BY p.startDrawDate DESC ')
            ->setParameters(['user_id' => $userId, 'actual_date' => date('Y-m-d')])
            ->getResult();
        return $result;
    }

    public function getNewRegistrations($data)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('displaydate', 'created');
        $rsm->addScalarResult('country', 'country');
        return $this->entityManager
            ->createNativeQuery(
                "SELECT COUNT(id) as id, DATE_FORMAT(created, '%Y-%M-%d') as displaydate, country
                FROM users
                WHERE created BETWEEN '" . date('Y-m-d', strtotime($data['dateFrom'])) . " 00:00:01' AND '" . date('Y-m-d', strtotime($data['dateTo'])) . " 23:59:59'
                AND country IN ('" . implode("','", $data['countries']) . "')
                GROUP BY id", $rsm)->getResult();

    }

    public function getNewDepositors($data)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('displaydate', 'created');
        $rsm->addScalarResult('country', 'country');
        $rsm->addScalarResult('date', 'date');
        return $this->entityManager
            ->createNativeQuery("
                SELECT DISTINCT(t.user_id) as id, DATE_FORMAT(created, '%Y-%M-%d') as displaydate, country
                FROM transactions t 
                LEFT JOIN users u ON u.id = t.user_id 
                WHERE date BETWEEN created AND '" . date('Y-m-d', strtotime($data['dateTo'])) . " 23:59:59'
                AND created BETWEEN '" . date('Y-m-d', strtotime($data['dateFrom'])) . " 00:00:01' AND '" . date('Y-m-d', strtotime($data['dateTo'])) . " 23:59:59'
                AND u.country IN ('" . implode("','", $data['countries']) . "') 
                AND t.entity_type IN ('deposit', 'subscription_purchase')  GROUP BY t.user_id", $rsm)->getResult();
    }

    public function getActives($data)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('displaydate', 'created');
        $rsm->addScalarResult('country', 'country');
        return $this->entityManager
            ->createNativeQuery(
                "SELECT DISTINCT(t.user_id) as id, DATE_FORMAT(date, '%Y-%M-%d') as displaydate, country
                FROM transactions t
                LEFT JOIN users u ON t.user_id = u.id
                WHERE date BETWEEN '" . date('Y-m-d', strtotime($data['dateFrom'])) . " 00:00:01' AND '" . date('Y-m-d', strtotime($data['dateTo'])) . " 23:59:59'
                AND u.country IN ('" . implode("','", $data['countries']) . "')", $rsm)->getResult();

    }

    public function getActivesDay($data)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('displaydate', 'created');
        $rsm->addScalarResult('country', 'country');
        return $this->entityManager
            ->createNativeQuery(
                "SELECT COUNT(DISTINCT(t.user_id)) as id, DATE_FORMAT(date, '%d') as displaydate, country
                FROM transactions t
                LEFT JOIN users u ON t.user_id = u.id
                WHERE date BETWEEN '" . date('Y-m-d', strtotime($data['dateFrom'])) . " 00:00:01' AND '" . date('Y-m-d', strtotime($data['dateTo'])) . " 23:59:59'
                AND created BETWEEN '" . date('Y-m-d', strtotime($data['dateFrom'])) . " 00:00:01' AND '" . date('Y-m-d', strtotime($data['dateTo'])) . " 23:59:59'
                AND u.country IN ('" . implode("','", $data['countries']) . "')
                GROUP BY t.user_id, displaydate, country", $rsm)->getResult();

    }

    public function getActivesMonth($data)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('displaydate', 'created');
        $rsm->addScalarResult('country', 'country');
        return $this->entityManager
            ->createNativeQuery(
                "SELECT COUNT(DISTINCT(t.user_id)) as id, DATE_FORMAT(date, '%M') as displaydate, country
                FROM transactions t
                LEFT JOIN users u ON t.user_id = u.id
                WHERE date BETWEEN '" . date('Y-m-d', strtotime($data['dateFrom'])) . " 00:00:01' AND '" . date('Y-m-d', strtotime($data['dateTo'])) . " 23:59:59'
                AND created BETWEEN '" . date('Y-m-d', strtotime($data['dateFrom'])) . " 00:00:01' AND '" . date('Y-m-d', strtotime($data['dateTo'])) . " 23:59:59'
                AND u.country IN ('" . implode("','", $data['countries']) . "')
                GROUP BY t.user_id, displaydate, country", $rsm)->getResult();

    }

    public function getActivesYear($data)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('displaydate', 'created');
        $rsm->addScalarResult('country', 'country');
        return $this->entityManager
            ->createNativeQuery(
                "SELECT COUNT(DISTINCT(t.user_id)) as id, DATE_FORMAT(date, '%Y') as displaydate, country
                FROM transactions t
                LEFT JOIN users u ON t.user_id = u.id
                WHERE date BETWEEN '" . date('Y-m-d', strtotime($data['dateFrom'])) . " 00:00:01' AND '" . date('Y-m-d', strtotime($data['dateTo'])) . " 23:59:59'
                AND created BETWEEN '" . date('Y-m-d', strtotime($data['dateFrom'])) . " 00:00:01' AND '" . date('Y-m-d', strtotime($data['dateTo'])) . " 23:59:59'
                AND u.country IN ('" . implode("','", $data['countries']) . "')
                GROUP BY t.user_id, displaydate, country", $rsm)->getResult();

    }
//    public function getNewRegistrationsMobile($data){
//        $rsm = new ResultSetMapping();
//        $rsm->addScalarResult();
//
//    }
//    public function getNewDepositorsMobile($data){
//        $rsm = new ResultSetMapping();
//        $rsm->addScalarResult();
//
//    }
//    public function geConversionMobile($data){
//        $rsm = new ResultSetMapping();
//        $rsm->addScalarResult();
//
//    }
//    public function getActivesMobile($data){
//        $rsm = new ResultSetMapping();
//        $rsm->addScalarResult();
//
//    }
    public function getNumberBets($data)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('displaydate', 'created');
        $rsm->addScalarResult('country', 'country');
        return $this->entityManager
            ->createNativeQuery(
                "SELECT SUM(CASE
                            WHEN entity_type = 'ticket_purchase' AND t.date BETWEEN '" . date('Y-m-d', strtotime($data['dateFrom'])) . " 00:00:01' AND DATE(DATE_ADD('" . date('Y-m-d', strtotime($data['dateTo'])) . " 23:59:59', INTERVAL +1 DAY)) THEN (SUBSTRING_INDEX(SUBSTRING_INDEX(data, '#', 2), '#', -1)) 
                            WHEN entity_type = 'automatic_purchase' AND t.date BETWEEN '" . date('Y-m-d', strtotime($data['dateFrom'])) . " 00:00:01' AND DATE(DATE_ADD('" . date('Y-m-d', strtotime($data['dateTo'])) . " 23:59:59', INTERVAL +1 DAY)) THEN 1
                            ELSE 0
                            END) as id, DATE_FORMAT(date, '%Y-%M-%d') as displaydate, u.country
                FROM transactions t
                LEFT JOIN users u ON t.user_id = u.id
                WHERE date BETWEEN '" . date('Y-m-d', strtotime($data['dateFrom'])) . " 00:00:01' AND DATE(DATE_ADD('" . date('Y-m-d', strtotime($data['dateTo'])) . "', INTERVAL +1 DAY))
                AND u.country IN ('" . implode("','", $data['countries']) . "')
                AND created IS NOT NULL
                GROUP BY displaydate, country", $rsm)->getResult();

    }

    public function getTotalBetsAmount($data)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('displaydate', 'created');
        $rsm->addScalarResult('country', 'country');
        return $this->entityManager
            ->createNativeQuery(
                "SELECT sum(CASE
                            WHEN entity_type = 'ticket_purchase' AND t.date BETWEEN '" . date('Y-m-d', strtotime($data['dateFrom'])) . " 00:00:01' AND DATE(DATE_ADD('" . date('Y-m-d', strtotime($data['dateTo'])) . "', INTERVAL +1 DAY)) THEN (SUBSTRING_INDEX(SUBSTRING_INDEX(data, '#', 2), '#', -1) * 300) 
                            WHEN entity_type = 'automatic_purchase' AND t.date BETWEEN '" . date('Y-m-d', strtotime($data['dateFrom'])) . " 00:00:01' AND DATE(DATE_ADD('" . date('Y-m-d', strtotime($data['dateTo'])) . "', INTERVAL +1 DAY)) THEN (wallet_before_subscription_amount - wallet_after_subscription_amount)
                            ELSE 0
                            END) as id, DATE_FORMAT(date, '%Y-%M-%d') as displaydate, u.country
                FROM transactions t
                LEFT JOIN users u ON t.user_id = u.id
                WHERE date BETWEEN '" . date('Y-m-d', strtotime($data['dateFrom'])) . " 00:00:01' AND DATE(DATE_ADD('" . date('Y-m-d', strtotime($data['dateTo'])) . "', INTERVAL +1 DAY))
                AND u.country IN ('" . implode("','", $data['countries']) . "')
                AND created IS NOT NULL
                GROUP BY displaydate, country", $rsm)->getResult();
    }

    public function getNumberDeposits($data)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('displaydate', 'created');
        $rsm->addScalarResult('country', 'country');
        return $this->entityManager
            ->createNativeQuery(
                "SELECT COUNT(t.id) as id, DATE_FORMAT(date, '%Y-%M-%d') as displaydate, u.country
                FROM transactions t
                LEFT JOIN users u ON t.user_id = u.id
                WHERE date BETWEEN '" . date('Y-m-d', strtotime($data['dateFrom'])) . " 00:00:01' AND DATE(DATE_ADD('" . date('Y-m-d', strtotime($data['dateTo'])) . "', INTERVAL +1 DAY))
                AND u.country IN ('" . implode("','", $data['countries']) . "')
                AND t.entity_type IN ('deposit', 'subscription_purchase')
                AND created IS NOT NULL
                GROUP BY displaydate, country", $rsm)->getResult();
    }

    public function getDepositAmount($data)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('displaydate', 'created');
        $rsm->addScalarResult('country', 'country');
        return $this->entityManager
            ->createNativeQuery(
                "SELECT SUM((t.wallet_after_uploaded_amount - t.wallet_before_uploaded_amount) + (t.wallet_after_subscription_amount - t.wallet_before_subscription_amount) ) as id, DATE_FORMAT(date, '%Y-%M-%d') as displaydate, u.country
                FROM transactions t
                LEFT JOIN users u ON t.user_id = u.id
                WHERE date BETWEEN '" . date('Y-m-d', strtotime($data['dateFrom'])) . " 00:00:01' AND DATE(DATE_ADD('" . date('Y-m-d', strtotime($data['dateTo'])) . "', INTERVAL +1 DAY))
                AND u.country IN ('" . implode("','", $data['countries']) . "')
                AND t.entity_type IN ('deposit', 'subscription_purchase')
                AND created IS NOT NULL
                GROUP BY displaydate, country", $rsm)->getResult();

    }

    public function getNumberWithdrawals($data)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('displaydate', 'created');
        $rsm->addScalarResult('country', 'country');
        return $this->entityManager
            ->createNativeQuery(
                "SELECT COUNT(t.id) as id, DATE_FORMAT(date, '%Y-%M-%d') as displaydate, u.country
                FROM transactions t
                LEFT JOIN users u ON t.user_id = u.id
                WHERE date BETWEEN '" . date('Y-m-d', strtotime($data['dateFrom'])) . " 00:00:01' AND DATE(DATE_ADD('" . date('Y-m-d', strtotime($data['dateTo'])) . "', INTERVAL +1 DAY))
                AND u.country IN ('" . implode("','", $data['countries']) . "')
                AND t.entity_type IN ('winnings_withdraw')
                AND created IS NOT NULL
                GROUP BY displaydate, country", $rsm)->getResult();

    }

    public function getWithdrawalAmount($data)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('displaydate', 'created');
        $rsm->addScalarResult('country', 'country');
        return $this->entityManager
            ->createNativeQuery(
                "SELECT SUM(t.wallet_after_uploaded_amount - t.wallet_before_uploaded_amount) as id, DATE_FORMAT(date, '%Y-%M-%d') as displaydate, u.country
                FROM transactions t
                LEFT JOIN users u ON t.user_id = u.id
                WHERE date BETWEEN '" . date('Y-m-d', strtotime($data['dateFrom'])) . " 00:00:01' AND DATE(DATE_ADD('" . date('Y-m-d', strtotime($data['dateTo'])) . "', INTERVAL +1 DAY))
                AND u.country IN ('" . implode("','", $data['countries']) . "')
                AND t.entity_type IN ('winnings_withdraw')
                AND created IS NOT NULL
                GROUP BY displaydate, country", $rsm)->getResult();

    }

//    public function getGrossGamingRevenue($data){
//        $rsm = new ResultSetMapping();
//        $rsm->addScalarResult();
//
//    }
//    public function getNetGamingRevenue($data){
//        $rsm = new ResultSetMapping();
//        $rsm->addScalarResult();
//    }

//    public function getAverageRevenuePerUser($data){
//        $rsm = new ResultSetMapping();
//        $rsm->addScalarResult();
//    }

//    public function getBonusCost($data){
//        $rsm = new ResultSetMapping();
//        $rsm->addScalarResult();
//    }

    public function getPlayerWinnings($data)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('displaydate', 'created');
        $rsm->addScalarResult('country', 'country');
        return $this->entityManager
            ->createNativeQuery(
                "SELECT SUM(t.wallet_after_winnings_amount - t.wallet_before_winnings_amount) as id, DATE_FORMAT(date, '%Y-%M-%d') as displaydate, u.country
                FROM transactions t
                LEFT JOIN users u ON t.user_id = u.id
                WHERE date BETWEEN '" . date('Y-m-d', strtotime($data['dateFrom'])) . " 00:00:01' AND DATE(DATE_ADD('" . date('Y-m-d', strtotime($data['dateTo'])) . "', INTERVAL +1 DAY))
                AND u.country IN ('" . implode("','", $data['countries']) . "')
                AND t.entity_type IN ('winnings_received')
                AND created IS NOT NULL
                GROUP BY displaydate, country", $rsm)->getResult();
    }

//    public function getCustomerLifetimeValue($data){
//        $rsm = new ResultSetMapping();
//        $rsm->addScalarResult();
//    }

    /**
     * @param $id
     * @param $drawDates
     *
     * @return array
     */
    public function getEuromillionsDrawDetailsByIdAndDates($id, $drawDates)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('email', 'email');
        $rsm->addScalarResult('country', 'country');
        $rsm->addScalarResult('transactionID', 'transactionID');
        //$rsm->addScalarResult('betId','betId'); No se puede poner pq no hay relación con el betId
        $rsm->addScalarResult('purchaseDate', 'purchaseDate');
        $rsm->addScalarResult('entity_type', 'entity_type');
        $rsm->addScalarResult('data', 'data');
        $rsm->addScalarResult('automaticMovement', 'automaticMovement');

        return $this->entityManager
            ->createNativeQuery('SELECT distinct t.id as transactionID, u.email as email, u.country as country, t.date as purchaseDate, t.entity_type, t.data, (wallet_before_subscription_amount - wallet_after_subscription_amount) as automaticMovement
                            FROM bets b
                            INNER JOIN play_configs pc ON b.playConfig_id = pc.id
                            INNER JOIN users u ON pc.user_id = u.id
                            INNER JOIN transactions t ON pc.user_id = t.user_id
                            WHERE euromillions_draw_id = ' . $id . ' and (t.entity_type = "ticket_purchase" || t.entity_type = "automatic_purchase") and
                            t.date BETWEEN "' . $drawDates['actualDrawDate']->format('Y-m-d H:i:s') . '" AND "' . $drawDates['nextDrawDate']->format('Y-m-d H:i:s') . '"
                            ORDER BY purchaseDate desc',
                $rsm)->getResult();
    }

    /**
     * @param $drawDates
     *
     * @return array
     */
    public function getEuromillionsDrawDetailsBetweenDrawDates($drawDates)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('totalBets','totalBets');
        $rsm->addScalarResult('grossSales','grossSales');
        $rsm->addScalarResult('grossMargin','grossMargin');

        return  $this->entityManager
            ->createNativeQuery('SELECT sum(SUBSTRING_INDEX(SUBSTRING_INDEX(data, "#", 2), "#", -1)) as totalBets, sum(CASE
                                        WHEN entity_type = "ticket_purchase" THEN (SUBSTRING_INDEX(SUBSTRING_INDEX(data, "#", 2), "#", -1) * 300) 
                                        WHEN entity_type = "automatic_purchase" THEN (wallet_before_subscription_amount - wallet_after_subscription_amount)
                                        ELSE 0
                                        END
                                    ) as grossSales, sum(CASE
                                        WHEN entity_type = "ticket_purchase" THEN (SUBSTRING_INDEX(SUBSTRING_INDEX(data, "#", 2), "#", -1) * 300) - (SUBSTRING_INDEX(SUBSTRING_INDEX(data, "#", 2), "#", -1) * 250)
                                        WHEN entity_type = "automatic_purchase" THEN (wallet_before_subscription_amount - wallet_after_subscription_amount - 250)
                                        ELSE 0
                                        END
                                    ) as grossMargin
                            FROM transactions
                            WHERE (entity_type = "ticket_purchase" || entity_type = "automatic_purchase") and
                            date BETWEEN "' . $drawDates['actualDrawDate']->format('Y-m-d H:i:s') . '" AND "' . $drawDates['nextDrawDate']->format('Y-m-d H:i:s') . '"'
                , $rsm)->getResult();
    }

    /**
     * @param $betId
     *
     * @return array
     */
    public function getNumbersPlayedByBetId($betId)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('line_regular_number_one','line_regular_number_one');
        $rsm->addScalarResult('line_regular_number_two','line_regular_number_two');
        $rsm->addScalarResult('line_regular_number_three','line_regular_number_three');
        $rsm->addScalarResult('line_regular_number_four','line_regular_number_four');
        $rsm->addScalarResult('line_regular_number_five','line_regular_number_five');
        $rsm->addScalarResult('line_lucky_number_one','line_lucky_number_one');
        $rsm->addScalarResult('line_lucky_number_two','line_lucky_number_two');

        return  $this->entityManager
            ->createNativeQuery('SELECT line_regular_number_one, line_regular_number_two, line_regular_number_three, line_regular_number_four, line_regular_number_five, line_lucky_number_one, line_lucky_number_two
                FROM play_configs
                WHERE id = ' . $betId
                , $rsm)->getResult()[0];
    }

    public function getDepositorsD0($data)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('displaydate', 'created');
        $rsm->addScalarResult('country', 'country');
        return $this->entityManager
            ->createNativeQuery("
                SELECT DISTINCT(t.user_id) as id, DATE_FORMAT(date, '%Y-%M-%d') as displaydate, country
                FROM transactions t 
                LEFT JOIN users u ON u.id = t.user_id 
                WHERE date BETWEEN DATE_FORMAT(created, '%Y-%m-%d 00:00:01') AND DATE_FORMAT(DATE_ADD(created, INTERVAL +1 DAY), '%Y-%m-%d 00:00:01') 
                AND created BETWEEN '" . date('Y-m-d', strtotime($data['dateFrom'])) . " 00:00:01' AND '" . date('Y-m-d', strtotime($data['dateTo'])) . " 23:59:59'
                AND u.country IN ('" . implode("','", $data['countries']) . "')
                AND t.entity_type IN ('deposit', 'subscription_purchase') GROUP BY u.id", $rsm)->getResult();
    }

    public function getDepositorsD1($data)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('displaydate', 'created');
        $rsm->addScalarResult('country', 'country');
        return $this->entityManager
            ->createNativeQuery("
                SELECT DISTINCT(t.user_id) as id, DATE_FORMAT(date, '%Y-%M-%d') as displaydate, country
                FROM transactions t 
                LEFT JOIN users u ON u.id = t.user_id 
                WHERE date BETWEEN DATE_FORMAT(DATE_ADD(created, INTERVAL +1 DAY), '%Y-%m-%d 00:00:01') AND DATE_FORMAT(DATE_ADD(created, INTERVAL +2 DAY), '%Y-%m-%d 00:00:01') 
                AND created BETWEEN '" . date('Y-m-d', strtotime($data['dateFrom'])) . " 00:00:01' AND '" . date('Y-m-d', strtotime($data['dateTo'])) . " 23:59:59'
                AND u.country IN ('" . implode("','", $data['countries']) . "')
                AND t.entity_type IN ('deposit', 'subscription_purchase') GROUP BY u.id", $rsm)->getResult();
    }

    public function getDepositorsD2($data)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('displaydate', 'created');
        $rsm->addScalarResult('country', 'country');
        return $this->entityManager
            ->createNativeQuery("
                SELECT DISTINCT(t.user_id) as id, DATE_FORMAT(date, '%Y-%M-%d') as displaydate, country
                FROM transactions t 
                LEFT JOIN users u ON u.id = t.user_id 
                WHERE date BETWEEN DATE(DATE_ADD(created, INTERVAL +2 DAY)) AND '" . date('Y-m-d', strtotime($data['dateTo'])) . " 23:59:59'
                AND created BETWEEN '" . date('Y-m-d', strtotime($data['dateFrom'])) . " 00:00:01' AND '" . date('Y-m-d', strtotime($data['dateTo'])) . " 23:59:59'
                AND u.country IN ('" . implode("','", $data['countries']) . "')
                AND t.entity_type IN ('deposit', 'subscription_purchase') GROUP BY u.id", $rsm)->getResult();
    }

    public function getJustInactives($data)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('displaydate', 'created');
        $rsm->addScalarResult('country', 'country');
        return $this->entityManager
            ->createNativeQuery(
                "SELECT DISTINCT(t.user_id) as id, DATE_FORMAT(date, '%Y-%M-%d') as displaydate, country
                FROM transactions t
                LEFT JOIN users u ON t.user_id = u.id
                WHERE u.id IN(SELECT t.user_id
                FROM transactions t
                LEFT JOIN users u ON t.user_id = u.id
                WHERE date > DATE(DATE_ADD('" . date('Y-m-d', strtotime($data['dateTo'])) . " 23:59:59', INTERVAL -14 DAY)) 
                AND date < DATE(DATE_ADD('" . date('Y-m-d', strtotime($data['dateTo'])) . "', INTERVAL -7 DAY)))
                AND u.id NOT IN(SELECT t.user_id
                FROM transactions t
                LEFT JOIN users u ON t.user_id = u.id
                WHERE date > DATE(DATE_ADD('" . date('Y-m-d', strtotime($data['dateTo'])) . " 23:59:59', INTERVAL -7 DAY)))
                AND u.id NOT IN(SELECT t.user_id
                FROM transactions t
                LEFT JOIN users u ON t.user_id = u.id
                WHERE date < DATE(DATE_ADD('" . date('Y-m-d', strtotime($data['dateTo'])) . " 23:59:59', INTERVAL -14 DAY)))", $rsm)->getResult();
    }

    public function getInactives($data)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('displaydate', 'created');
        $rsm->addScalarResult('country', 'country');
        return $this->entityManager
            ->createNativeQuery(
                "SELECT DISTINCT(t.user_id) as id, DATE_FORMAT(date, '%Y-%M-%d') as displaydate, country
                FROM transactions t
                LEFT JOIN users u ON t.user_id = u.id
                WHERE u.id IN(SELECT t.user_id
                FROM transactions t
                LEFT JOIN users u ON t.user_id = u.id
                WHERE date > DATE(DATE_ADD('" . date('Y-m-d', strtotime($data['dateTo'])) . " 23:59:59', INTERVAL -30 DAY)) 
                AND date < DATE(DATE_ADD('" . date('Y-m-d', strtotime($data['dateTo'])) . "', INTERVAL -15 DAY)))
                AND u.id NOT IN(SELECT t.user_id
                FROM transactions t
                LEFT JOIN users u ON t.user_id = u.id
                WHERE date > DATE(DATE_ADD('" . date('Y-m-d', strtotime($data['dateTo'])) . " 23:59:59', INTERVAL -15 DAY)))
                AND u.id NOT IN(SELECT t.user_id
                FROM transactions t
                LEFT JOIN users u ON t.user_id = u.id
                WHERE date < DATE(DATE_ADD('" . date('Y-m-d', strtotime($data['dateTo'])) . " 23:59:59', INTERVAL -30 DAY)))", $rsm)->getResult();

    }

    public function getDormant($data)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('displaydate', 'created');
        $rsm->addScalarResult('country', 'country');
        return $this->entityManager
            ->createNativeQuery(
                "SELECT DISTINCT(t.user_id) as id, DATE_FORMAT(date, '%Y-%M-%d') as displaydate, country
                FROM transactions t
                LEFT JOIN users u ON t.user_id = u.id
                WHERE u.id IN(SELECT t.user_id
                FROM transactions t
                LEFT JOIN users u ON t.user_id = u.id
                WHERE date > DATE(DATE_ADD('" . date('Y-m-d', strtotime($data['dateTo'])) . " 23:59:59', INTERVAL -45 DAY)) 
                AND date < DATE(DATE_ADD('" . date('Y-m-d', strtotime($data['dateTo'])) . "', INTERVAL -30 DAY)))
                AND u.id NOT IN(SELECT t.user_id
                FROM transactions t
                LEFT JOIN users u ON t.user_id = u.id
                WHERE date > DATE(DATE_ADD('" . date('Y-m-d', strtotime($data['dateTo'])) . " 23:59:59', INTERVAL -30 DAY)))
                AND u.id NOT IN(SELECT t.user_id
                FROM transactions t
                LEFT JOIN users u ON t.user_id = u.id
                WHERE date < DATE(DATE_ADD('" . date('Y-m-d', strtotime($data['dateTo'])) . " 23:59:59', INTERVAL -45 DAY)))", $rsm)->getResult();
    }

    public function getReactivatedJI($data)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('displaydate', 'created');
        $rsm->addScalarResult('country', 'country');
        return $this->entityManager
            ->createNativeQuery(
                "SELECT DISTINCT(u.id) as id, DATE_FORMAT(date, '%Y-%M-%d') as displaydate, country
                FROM users u
                LEFT JOIN transactions t ON t.user_id = u.id
                WHERE user_id IN(SELECT u.id as id
                FROM users u
                LEFT JOIN transactions t ON t.user_id = u.id
                WHERE u.id NOT IN (SELECT DISTINCT(t.user_id) as id
                FROM transactions t
                LEFT JOIN users u ON t.user_id = u.id
                WHERE date BETWEEN DATE(DATE_ADD('" . date('Y-m-d', strtotime($data['dateTo'])) . " 23:59:59', INTERVAL -14 DAY)) AND DATE(DATE_ADD('" . date('Y-m-d', strtotime($data['dateTo'])) . " 23:59:59', INTERVAL -7 DAY))))
                AND date BETWEEN '" . date('Y-m-d', strtotime($data['dateFrom'])) . " 00:00:01' AND '" . date('Y-m-d', strtotime($data['dateTo'])) . " 23:59:59'
                GROUP BY u.id", $rsm)->getResult();
    }

    public function getReactivatedIN($data)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('displaydate', 'created');
        $rsm->addScalarResult('country', 'country');
        return $this->entityManager
            ->createNativeQuery(
                "SELECT DISTINCT(u.id) as id, DATE_FORMAT(date, '%Y-%M-%d') as displaydate, country
                FROM users u
                LEFT JOIN transactions t ON t.user_id = u.id
                WHERE user_id IN(SELECT u.id as id
                FROM users u
                LEFT JOIN transactions t ON t.user_id = u.id
                WHERE u.id NOT IN (SELECT DISTINCT(t.user_id) as id
                FROM transactions t
                LEFT JOIN users u ON t.user_id = u.id
                WHERE date BETWEEN DATE(DATE_ADD('" . date('Y-m-d', strtotime($data['dateTo'])) . " 23:59:59', INTERVAL -30 DAY)) AND DATE(DATE_ADD('" . date('Y-m-d', strtotime($data['dateTo'])) . " 23:59:59', INTERVAL -15 DAY))))
                AND date BETWEEN '" . date('Y-m-d', strtotime($data['dateFrom'])) . " 00:00:01' AND '" . date('Y-m-d', strtotime($data['dateTo'])) . " 23:59:59'
                GROUP BY u.id", $rsm)->getResult();

    }

    public function getReactivatedDOR($data)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('displaydate', 'created');
        $rsm->addScalarResult('country', 'country');
        return $this->entityManager
            ->createNativeQuery(
                "SELECT DISTINCT(u.id) as id, DATE_FORMAT(date, '%Y-%M-%d') as displaydate, country
                FROM users u
                LEFT JOIN transactions t ON t.user_id = u.id
                WHERE user_id IN(SELECT u.id as id
                FROM users u
                LEFT JOIN transactions t ON t.user_id = u.id
                WHERE u.id NOT IN (SELECT DISTINCT(t.user_id) as id
                FROM transactions t
                LEFT JOIN users u ON t.user_id = u.id
                WHERE date BETWEEN DATE(DATE_ADD('" . date('Y-m-d', strtotime($data['dateTo'])) . " 23:59:59', INTERVAL -45 DAY)) AND DATE(DATE_ADD('" . date('Y-m-d', strtotime($data['dateTo'])) . " 23:59:59', INTERVAL -31 DAY))))
                AND date BETWEEN '" . date('Y-m-d', strtotime($data['dateFrom'])) . " 00:00:01' AND '" . date('Y-m-d', strtotime($data['dateTo'])) . " 23:59:59'
                GROUP BY u.id", $rsm)->getResult();

    }

    public function getActivesBeginning($data)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('displaydate', 'created');
        $rsm->addScalarResult('country', 'country');
        return $this->entityManager
            ->createNativeQuery(
                "SELECT DISTINCT(t.user_id) as id, DATE_FORMAT(date, '%Y-%M-%d') as displaydate, country
                FROM transactions t
                LEFT JOIN users u ON t.user_id = u.id
                WHERE date BETWEEN DATE(DATE_ADD('" . date('Y-m-d', strtotime($data['dateFrom'])) . " 00:00:01', INTERVAL -7 DAY)) AND '" . date('Y-m-d', strtotime($data['dateFrom'])) . " 00:00:01'
                AND u.country IN ('" . implode("','", $data['countries']) . "')", $rsm)->getResult();

    }

    public function getActivesEnd($data)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('displaydate', 'created');
        $rsm->addScalarResult('country', 'country');
        return $this->entityManager
            ->createNativeQuery(
                "SELECT DISTINCT(t.user_id) as id, DATE_FORMAT(date, '%Y-%M-%d') as displaydate, country
                FROM transactions t
                LEFT JOIN users u ON t.user_id = u.id
                WHERE date BETWEEN DATE(DATE_ADD('" . date('Y-m-d', strtotime($data['dateTo'])) . " 23:59:59', INTERVAL -7 DAY)) AND '" . date('Y-m-d', strtotime($data['dateTo'])) . " 23:59:59'
                AND u.country IN ('" . implode("','", $data['countries']) . "')", $rsm)->getResult();

    }

    public function getGrossGamingRevenue($data)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('displaydate', 'created');
        $rsm->addScalarResult('country', 'country');
        return $this->entityManager
            ->createNativeQuery("SELECT SUM(CASE 
				WHEN entity_type = 'automatic_purchase' THEN (wallet_before_subscription_amount - wallet_after_subscription_amount - 250)
                WHEN entity_type = 'ticket_purchase' AND (wallet_before_subscription_amount - wallet_after_subscription_amount) > 0 THEN (wallet_before_subscription_amount - wallet_after_subscription_amount - ((SUBSTRING_INDEX(SUBSTRING_INDEX(data, '#', 2), '#', -1) * 250)))
                WHEN entity_type = 'ticket_purchase' AND (wallet_before_subscription_amount - wallet_after_subscription_amount) <= 0 THEN (SUBSTRING_INDEX(SUBSTRING_INDEX(data, '#', 2), '#', -1) * 50)
			END) as id, DATE_FORMAT(date, '%Y-%M-%d') as displaydate, u.country
                                FROM transactions t
                                LEFT JOIN users u ON t.user_id = u.id
                                WHERE entity_type in ('ticket_purchase', 'automatic_purchase')
                                AND date BETWEEN '" . date('Y-m-d', strtotime($data['dateFrom'])) . " 00:00:01' AND DATE(DATE_ADD('" . date('Y-m-d', strtotime($data['dateTo'])) . "', INTERVAL +1 DAY))
                                AND created IS NOT NULL
                                AND u.country IN ('" . implode("','", $data['countries']) . "')
                group by displaydate, country",
                $rsm)->getResult();
    }

}