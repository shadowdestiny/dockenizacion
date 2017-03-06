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
//            var_dump($result);die();
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
                . ' WHERE p.user = :user_id AND p.active = :active '
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
                . ' WHERE p.user = :user_id AND e.draw_date < :actual_date '
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
                WHERE created BETWEEN '" . date('Y-m-d', strtotime($data['dateFrom'])) . "' AND '" . date('Y-m-d', strtotime($data['dateTo'])) . "'
                AND country IN ('" . implode("','", $data['countries']) . "')
                GROUP BY displaydate, country", $rsm)->getResult();

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
                SELECT COUNT(date) as id, DATE_FORMAT(created, '%Y-%M-%d') as displaydate, country
                FROM transactions t 
                LEFT JOIN users u ON u.id = t.user_id 
                WHERE date BETWEEN '" . date('Y-m-d', strtotime($data['dateFrom'])) . "' AND '" . date('Y-m-d', strtotime($data['dateTo'])) . "'
                AND u.country IN ('" . implode("','", $data['countries']) . "') 
                AND t.entity_type = 'deposit' GROUP BY user_id, displaydate, country", $rsm)->getResult();
    }

    public function getActives($data)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('displaydate', 'created');
        $rsm->addScalarResult('country', 'country');
        return $this->entityManager
            ->createNativeQuery(
                "SELECT COUNT(DISTINCT(t.user_id)) as id, DATE_FORMAT(created, '%Y-%M-%d') as displaydate, country
                FROM transactions t
                LEFT JOIN users u ON t.user_id = u.id
                WHERE date BETWEEN '" . date('Y-m-d', strtotime($data['dateFrom'])) . "' AND '" . date('Y-m-d', strtotime($data['dateTo'])) . "'
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
                "SELECT COUNT(t.id) as id, DATE_FORMAT(u.created, '%Y-%M-%d') as displaydate, u.country
                FROM transactions t
                LEFT JOIN users u ON t.user_id = u.id
                WHERE date BETWEEN '" . date('Y-m-d', strtotime($data['dateFrom'])) . "' AND '" . date('Y-m-d', strtotime($data['dateTo'])) . "'
                AND u.country IN ('" . implode("','", $data['countries']) . "')
                AND t.entity_type = 'ticket_purchase'
                GROUP BY displaydate, country", $rsm)->getResult();

    }

    public function getTotalBets($data)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('displaydate', 'created');
        $rsm->addScalarResult('country', 'country');
        return $this->entityManager
            ->createNativeQuery(
                "SELECT COUNT(t.id) as id, DATE_FORMAT(u.created, '%Y-%M-%d') as displaydate, u.country
                FROM transactions t
                LEFT JOIN users u ON t.user_id = u.id
                WHERE date BETWEEN '" . date('Y-m-d', strtotime($data['dateFrom'])) . "' AND '" . date('Y-m-d', strtotime($data['dateTo'])) . "'
                AND u.country IN ('" . implode("','", $data['countries']) . "')
                AND t.entity_type IN ('ticket_purchase', ' automatic_purchase')
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
                "SELECT COUNT(t.id) as id, DATE_FORMAT(u.created, '%Y-%M-%d') as displaydate, u.country
                FROM transactions t
                LEFT JOIN users u ON t.user_id = u.id
                WHERE date BETWEEN '" . date('Y-m-d', strtotime($data['dateFrom'])) . "' AND '" . date('Y-m-d', strtotime($data['dateTo'])) . "'
                AND u.country IN ('" . implode("','", $data['countries']) . "')
                AND t.entity_type IN ('deposit', 'manual_deposit', 'subscription_purchase')
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
                "SELECT SUM(t.wallet_after_uploaded_amount - t.wallet_before_uploaded_amount) as id, DATE_FORMAT(u.created, '%Y-%M-%d') as displaydate, u.country
                FROM transactions t
                LEFT JOIN users u ON t.user_id = u.id
                WHERE date BETWEEN '" . date('Y-m-d', strtotime($data['dateFrom'])) . "' AND '" . date('Y-m-d', strtotime($data['dateTo'])) . "'
                AND u.country IN ('" . implode("','", $data['countries']) . "')
                AND t.entity_type IN ('deposit', 'manual_deposit')
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
                "SELECT COUNT(t.id) as id, DATE_FORMAT(u.created, '%Y-%M-%d') as displaydate, u.country
                FROM transactions t
                LEFT JOIN users u ON t.user_id = u.id
                WHERE date BETWEEN '" . date('Y-m-d', strtotime($data['dateFrom'])) . "' AND '" . date('Y-m-d', strtotime($data['dateTo'])) . "'
                AND u.country IN ('" . implode("','", $data['countries']) . "')
                AND t.entity_type IN ('winnings_withdraw')
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
                "SELECT SUM(t.wallet_after_uploaded_amount - t.wallet_before_uploaded_amount) as id, DATE_FORMAT(u.created, '%Y-%M-%d') as displaydate, u.country
                FROM transactions t
                LEFT JOIN users u ON t.user_id = u.id
                WHERE date BETWEEN '" . date('Y-m-d', strtotime($data['dateFrom'])) . "' AND '" . date('Y-m-d', strtotime($data['dateTo'])) . "'
                AND u.country IN ('" . implode("','", $data['countries']) . "')
                AND t.entity_type IN ('winnings_withdraw')
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
                "SELECT SUM(t.wallet_after_uploaded_amount - t.wallet_before_uploaded_amount) as id, DATE_FORMAT(u.created, '%Y-%M-%d') as displaydate, u.country
                FROM transactions t
                LEFT JOIN users u ON t.user_id = u.id
                WHERE date BETWEEN '" . date('Y-m-d', strtotime($data['dateFrom'])) . "' AND '" . date('Y-m-d', strtotime($data['dateTo'])) . "'
                AND u.country IN ('" . implode("','", $data['countries']) . "')
                AND t.entity_type IN ('winnings_received')
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
        $rsm->addScalarResult('email','email');
        $rsm->addScalarResult('country','country');
        $rsm->addScalarResult('transactionID','transactionID');
        //$rsm->addScalarResult('betId','betId'); No se puede poner pq no hay relación con el betId
        $rsm->addScalarResult('purchaseDate','purchaseDate');
        $rsm->addScalarResult('entity_type','entity_type');
        $rsm->addScalarResult('data','data');
        $rsm->addScalarResult('automaticMovement','automaticMovement');

        return  $this->entityManager
            ->createNativeQuery('SELECT distinct u.email as email, u.country as country, t.transactionID, t.date as purchaseDate, t.entity_type, t.data, (wallet_before_subscription_amount - wallet_after_subscription_amount) as automaticMovement
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
            ->createNativeQuery('SELECT count(*) as totalBets, sum(CASE
                                        WHEN entity_type = "ticket_purchase" THEN (SUBSTRING(data, 3, 1) * 300) 
                                        WHEN entity_type = "automatic_purchase" THEN (wallet_before_subscription_amount - wallet_after_subscription_amount)
                                        ELSE 0
                                        END
                                    ) as grossSales, sum(CASE
                                        WHEN entity_type = "ticket_purchase" THEN (SUBSTRING(data, 3, 1) * 300) - (SUBSTRING(data, 3, 1) * 250)
                                        WHEN entity_type = "automatic_purchase" THEN (wallet_before_subscription_amount - wallet_after_subscription_amount - 250)
                                        ELSE 0
                                        END
                                    ) as grossMargin
                            FROM transactions
                            WHERE (entity_type = "ticket_purchase" || entity_type = "automatic_purchase") and
                            date BETWEEN "' . $drawDates['actualDrawDate']->format('Y-m-d H:i:s') . '" AND "' . $drawDates['nextDrawDate']->format('Y-m-d H:i:s') . '"'
                , $rsm)->getResult();
    }
}