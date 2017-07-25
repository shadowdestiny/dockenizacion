<?php
namespace EuroMillions\web\repositories;


use Doctrine\ORM\Query\ResultSetMapping;
use EuroMillions\web\entities\Bet;

class PlayConfigRepository extends RepositoryBase
{
    public function getActivePlayConfigsByUser($userId)
    {
        return $this->getPlayConfigsByUser($userId, '1');
    }

    public function getInactivePlayConfigsByUser($userId)
    {
        return $this->getPlayConfigsByUser($userId, '0');
    }


    /**
     * @return array
     */
    public function getPlayConfigsByDrawDayAndDate(\DateTime $day)
    {
        $result = $this->getEntityManager()
            ->createQuery(
                ' SELECT p'
                . ' FROM ' . $this->getEntityName() . ' p'
                . ' WHERE p.active = 1 AND :day BETWEEN p.startDrawDate AND p.lastDrawDate '
                . ' group by p.user')
            ->setParameters(['day' => $day])
            ->getResult();

        return $result;
    }

    /**
     * @param \DateTime $day
     * @return array
     */
    public function getPlayConfigsByDrawDayAndDateAndLottery(\DateTime $day)
    {
        $result = $this->getEntityManager()
            ->createQuery(
                ' SELECT p'
                . ' FROM ' . $this->getEntityName() . ' p'
                . ' WHERE p.active = 1 AND :day BETWEEN p.startDrawDate AND p.lastDrawDate '
                . ' ORDER BY p.lottery'
                . ' group by p.user')
            ->setParameters(['day' => $day])
            ->getResult();

        return $result;
    }


    public function getPlayConfigsLongEnded(\DateTime $day)
    {
        $result = $this->getEntityManager()
            ->createQuery(
                'SELECT p'
                . ' FROM ' . $this->getEntityName() . ' p'
                . ' WHERE p.active = 1 AND :day NOT BETWEEN p.startDrawDate and p.lastDrawDate '
                . ' group by p.user')
            ->setParameters(['day' => $day])
            ->getResult();
        return $result;
    }


    public function getPlayConfigsByUserAndDate($userId, \DateTime $day)
    {
        $result = $this->getEntityManager()
            ->createQuery(
                'SELECT p'
                . ' FROM ' . $this->getEntityName() . ' p'
                . ' WHERE p.user = :user_id AND p.active = 1 AND :day BETWEEN p.startDrawDate and p.lastDrawDate ')
            ->setParameters(['user_id' => $userId,'day' => $day])
            ->getResult();

        return $result;
    }


    /**
     * @param $userId
     * @param $active
     * @return array
     */
    protected function getPlayConfigsByUser($userId, $active)
    {

        $result = $this->getEntityManager()
            ->createQuery(
                'SELECT b'
                . ' FROM ' . '\EuroMillions\web\entities\Bet' . ' b INNER JOIN b.playConfig p '
                . ' WHERE p.user = :user_id AND p.active = :active and p.frequency = :frequency '
                . ' GROUP BY p.startDrawDate,p.line.regular_number_one,'
                . ' p.line.regular_number_two,p.line.regular_number_three, '
                . ' p.line.regular_number_four,p.line.regular_number_five, '
                . ' p.line.lucky_number_one, p.line.lucky_number_two '
                . ' ORDER BY p.startDrawDate DESC ')
            ->setParameters(['user_id' => $userId, 'active' => $active, 'frequency' => 1])
            ->getResult();

        $playConfigs = [];
        /** @var Bet $bet */
        foreach($result as $bet) {
            $playConfigs[] = $bet->getPlayConfig();
        }
//        $result = $this->getEntityManager()
//            ->createQuery(
//                'SELECT p'
//                . ' FROM ' . $this->getEntityName() . ' p '
//                . ' WHERE p.user = :user_id AND p.active = :active '
//                . ' GROUP BY p.startDrawDate,p.line.regular_number_one,'
//                . ' p.line.regular_number_two,p.line.regular_number_three, '
//                . ' p.line.regular_number_four,p.line.regular_number_five, '
//                . ' p.line.lucky_number_one, p.line.lucky_number_two '
//                . ' ORDER BY p.startDrawDate DESC ')
//            ->setParameters(['user_id' => $userId, 'active' => $active])
//            ->getResult();

        return $playConfigs;
    }

    public function getUsersWithPlayConfigsActive()
    {
        $result = $this->getEntityManager()
            ->createQuery(
                'SELECT p'
                . ' FROM ' . $this->getEntityName() . ' p INNER JOIN p.user u'
                . ' WHERE p.active = 1 GROUP BY u.line_regular_number_one, '
                . ' u.line_regular_number_two,u.line_regular_number_three, '
                . ' u.line_regular_number_four,u.line_regular_number_five, '
                . ' u.line_lucky_number_one, u.line_lucky_number_two')
            ->getResult();
        return $result;
    }

    /**
     * @param $userId
     * @param $nextDrawDate \DateTime
     *
     * @return array
     */
    public function getSubscriptionsByUserIdActive($userId, $nextDrawDate)
    {
        $receivedDate = clone $nextDrawDate;
        if ($receivedDate->format('N') == 5) {
            $receivedDate->modify('-3 days');
        } else {
            $receivedDate->modify('-4 days');
        }
        $receivedDate->setTime(19, 30, 00);

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('start_draw_date', 'start_draw_date');
        $rsm->addScalarResult('last_draw_date', 'last_draw_date');
        $rsm->addScalarResult('line_regular_number_one', 'line_regular_number_one');
        $rsm->addScalarResult('line_regular_number_two', 'line_regular_number_two');
        $rsm->addScalarResult('line_regular_number_three', 'line_regular_number_three');
        $rsm->addScalarResult('line_regular_number_four', 'line_regular_number_four');
        $rsm->addScalarResult('line_regular_number_five', 'line_regular_number_five');
        $rsm->addScalarResult('line_lucky_number_one', 'line_lucky_number_one');
        $rsm->addScalarResult('line_lucky_number_two', 'line_lucky_number_two');

        return $this->getEntityManager()
            ->createNativeQuery(
                'SELECT p.start_draw_date, p.last_draw_date, p.line_regular_number_one,
                            p.line_regular_number_two,
                            p.line_regular_number_three,
                            p.line_regular_number_four,
                            p.line_regular_number_five,
                            p.line_lucky_number_one,
                            p.line_lucky_number_two'
                . ' FROM bets b INNER JOIN play_configs p on b.playConfig_id = p.id  '
                . ' INNER JOIN log_validation_api lva ON lva.bet_id = b.id '
                . ' WHERE p.user_id = "' . $userId . '" AND p.active = 1 AND p.frequency > 1 '
                . ' AND last_draw_date >= "' . $nextDrawDate->format('Y-m-d') . '" AND received >= "' . $receivedDate->format('Y-m-d H:i:s') . '"
                    AND lva.status = "OK"
                    GROUP BY p.start_draw_date,
                            p.line_regular_number_one,
                            p.line_regular_number_two,
                            p.line_regular_number_three,
                            p.line_regular_number_four,
                            p.line_regular_number_five,
                            p.line_lucky_number_one,
                            p.line_lucky_number_two
                    ORDER BY p.start_draw_date ASC, p.last_draw_date ASC '
                , $rsm)->getResult();
    }

    /**
     * @param $userId
     *
     * @return array
     */
    public function getSubscriptionsByUserIdInactive($userId)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('start_draw_date', 'start_draw_date');
        $rsm->addScalarResult('last_draw_date', 'last_draw_date');
        $rsm->addScalarResult('line_regular_number_one', 'line_regular_number_one');
        $rsm->addScalarResult('line_regular_number_two', 'line_regular_number_two');
        $rsm->addScalarResult('line_regular_number_three', 'line_regular_number_three');
        $rsm->addScalarResult('line_regular_number_four', 'line_regular_number_four');
        $rsm->addScalarResult('line_regular_number_five', 'line_regular_number_five');
        $rsm->addScalarResult('line_lucky_number_one', 'line_lucky_number_one');
        $rsm->addScalarResult('line_lucky_number_two', 'line_lucky_number_two');

        return $this->getEntityManager()
            ->createNativeQuery(
                'SELECT p.start_draw_date, p.last_draw_date, p.line_regular_number_one,
                            p.line_regular_number_two,
                            p.line_regular_number_three,
                            p.line_regular_number_four,
                            p.line_regular_number_five,
                            p.line_lucky_number_one,
                            p.line_lucky_number_two'
                . ' FROM bets b INNER JOIN play_configs p on b.playConfig_id = p.id  '
                . ' WHERE p.user_id = "' . $userId . '" AND p.active = 0 AND p.frequency > 1 '
                . '    GROUP BY p.start_draw_date,
                            p.line_regular_number_one,
                            p.line_regular_number_two,
                            p.line_regular_number_three,
                            p.line_regular_number_four,
                            p.line_regular_number_five,
                            p.line_lucky_number_one,
                            p.line_lucky_number_two
                    ORDER BY p.start_draw_date ASC, p.last_draw_date ASC '
                , $rsm)->getResult();
    }

    /**
     * @param $lotteryId int
     *
     * @return array
     */
    public function getAllSubscriptionsActivesByLotteryId($lotteryId)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('user_id', 'user_id');

        return $this->getEntityManager()
            ->createNativeQuery(
                'SELECT user_id
                      FROM play_configs
                      WHERE frequency > 1 and active = 1 and lottery_id = ' . $lotteryId . '
                      ORDER BY user_id ASC'
                , $rsm)->getResult();
    }

    /**
     * @param $lotteryId int
     * @param $nextDrawDate \DateTime
     *
     * @return array
     */
    public function getAllSubscriptionsPlayedByLotteryId($lotteryId, $nextDrawDate)
    {

        $receivedDate = clone $nextDrawDate;
        if ($receivedDate->format('N') == 5) {
            $receivedDate->modify('-3 days');
        } else {
            $receivedDate->modify('-4 days');
        }
        $receivedDate->setTime(21, 30, 00);

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('user_id', 'user_id');

        return $this->getEntityManager()
            ->createNativeQuery(
                'SELECT user_id
                        FROM bets b INNER JOIN play_configs p on b.playConfig_id = p.id 
                        INNER JOIN log_validation_api lva ON lva.bet_id = b.id
                        WHERE p.active = 1 AND p.frequency > 1 AND status = "OK" AND lottery_id = ' . $lotteryId . '
                          AND last_draw_date >= "' . $nextDrawDate->format('Y-m-d') . '" AND received >= "' . $receivedDate->format('Y-m-d H:i:s') . '"
                        ORDER BY user_id ASC'
                , $rsm)->getResult();
    }

    public function getTotalByUserAndPlayForNextDraw( $userId , \DateTime $dateNextDraw )
    {
        $result = $this->getEntityManager()
            ->createQuery(
                'SELECT COUNT(p.id) '
                . ' FROM ' . $this->getEntityName() . ' p'
                . ' WHERE p.user = :user_id '
                . ' AND p.active = 1 '
                . ' AND :day BETWEEN p.startDrawDate and p.lastDrawDate ')
            ->setMaxResults(1)
            ->setParameters([
                'user_id' => $userId,
                'day' => $dateNextDraw,
            ])
            ->getResult();

        return (int) $result[0][1];
    }

    public function getPlayConfigsByCollectionIds(array $ids)
    {
        $result = $this->getEntityManager()
            ->createQuery(
                'SELECT p '
                . ' FROM ' . $this->getEntityName() . ' p'
                . ' WHERE p.id IN (:ids)')
            ->setParameters([
                'ids' => $ids
            ])
            ->getResult();
        return $result;
    }

    public function updateToInactives(\DateTime $dateTime)
    {
        $date = $dateTime->format('Y-m-d');
        $result = $this->getEntityManager()
            ->createQuery(
                'UPDATE '
                . $this->getEntityName() . ' p '
                . ' SET p.active=0'
                . ' WHERE :date > p.lastDrawDate')
            ->setParameters([
                'date' => $date
            ])
            ->execute();
        return $result;
    }

    /**
     * @param $number
     */
    public function substractNumFractionsToChristmasTicket($number)
    {
        $number = str_replace(',', '', $number);
        $this->getEntityManager()->getConnection()->executeQuery("UPDATE christmas_tickets SET n_fractions = n_fractions - 1 where number = '". $number ."'");
    }

    public function retrieveEuromillionsBundlePrice()
    {
        return [
            ['draws' => '1', 'description' => '1 Draw', 'price_description' => 'Line', 'price' => '1', 'discount' => 0, 'checked' => 'active'],
            ['draws' => '4', 'description' => '4 Draws', 'price_description' => 'Line', 'price' => '1', 'discount' => 0, 'checked' => ''],
            ['draws' => '8', 'description' => '8 Draws', 'price_description' => 'Month', 'price' => '8', 'discount' => 0, 'checked' => ''],
            ['draws' => '24', 'description' => '24 Draws', 'price_description' => 'Month', 'price' => '8', 'discount' => 1.25, 'checked' => ''],
            ['draws' => '48', 'description' => '48 Draws', 'price_description' => 'Month', 'price' => '8', 'discount' => 4.5, 'checked' => ''],
        ];
    }

}