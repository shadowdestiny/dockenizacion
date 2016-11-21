<?php

namespace EuroMillions\web\repositories;

use Doctrine\ORM\EntityManager;
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
        $rsm->addScalarResult('month','month');
        $rsm->addScalarResult('total_bets','total_bets');
        $rsm->addScalarResult('gross_sales','gross_sales');
        $rsm->addScalarResult('gross_margin','gross_margin');
        $rsm->addScalarResult('winnings','winnings');
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
        $rsm->addScalarResult('em','em');
        $rsm->addScalarResult('id','id');
        $rsm->addScalarResult('draw_date','draw_date');
        $rsm->addScalarResult('draw_status','draw_status');
        $rsm->addScalarResult('count_id','count_id');
        $rsm->addScalarResult('count_id_3','count_id_3');
        $rsm->addScalarResult('count_id_05','count_id_05');

        return $this->entityManager
            ->createNativeQuery(
                "select 'EM' as em, e.id as id, e.draw_date as draw_date, IF(e.draw_date < now(),'Finished','Open') as draw_status, count(b.id) as count_id, count(b.id) * 3.00 as count_id_3, count(b.id) * 0.50 as count_id_05
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
                  from users u;",$rsm)
            ->getResult();

    }
}