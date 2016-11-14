<?php


namespace EuroMillions\web\repositories;


use EuroMillions\web\entities\Transaction;

class ReportsRepository extends RepositoryBase
{

//    public function getMonthlySales($date)
//    {
//        $result = $this->getEntityManager()
//            ->createQuery(
//                'SELECT MONTHNAME(d.draw_date) as month,count(b.id) as total_bets, count(b.id) * 3.00 as gross_sales, count(1) * 0.50 as gross_margin,
//                (select SUM(t.wallet_after_winnings_amount)
//                FROM \EuroMillions\web\entities\Transaction t
//                where MONTHNAME(t.date)=MONTHNAME(d.draw_date)
//                    and entity_type='winnings_received') as winnings
//                from bets b
//                join euromillions_draws d on d.id=b.euromillions_draw_id
//                join play_configs p on p.id=b.playConfig_id
//                group by MONTH(d.draw_date')
//            ->getResult();
//
//        return $result;
//    }

}