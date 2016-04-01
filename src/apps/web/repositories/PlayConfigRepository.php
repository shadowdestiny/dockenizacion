<?php
namespace EuroMillions\web\repositories;

use EuroMillions\web\entities\Lottery;
use EuroMillions\web\vo\DrawDays;

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
        $drawDay = new DrawDays(date('w', $day->getTimestamp()));

        $result = $this->getEntityManager()
            ->createQuery(
                ' SELECT p'
                . ' FROM ' . $this->getEntityName() . ' p'
                . ' WHERE p.days.days LIKE :draw_days AND p.active = 1 AND :day BETWEEN p.startDrawDate AND p.lastDrawDate '
                . ' group by p.user')
            ->setParameters(['day' => $day, 'draw_days' => '%'.$drawDay->value().'%'])
            ->getResult();

        return $result;
    }

    /**
     * @param \DateTime $day
     * @return array
     */
    public function getPlayConfigsByDrawDayAndDateAndLottery(\DateTime $day)
    {
        $drawDay = new DrawDays(date('w', $day->getTimestamp()));
        $result = $this->getEntityManager()
            ->createQuery(
                ' SELECT p'
                . ' FROM ' . $this->getEntityName() . ' p'
                . ' WHERE p.days.days LIKE :draw_days AND p.active = 1 AND :day BETWEEN p.startDrawDate AND p.lastDrawDate '
                . ' ORDER BY p.lottery'
                . ' group by p.user')
            ->setParameters(['day' => $day, 'draw_days' => '%'.$drawDay->value().'%'])
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
                'SELECT p'
                . ' FROM ' . $this->getEntityName() . ' p'
                . ' WHERE p.user = :user_id AND p.active = :active')
            ->setParameters(['user_id' => $userId, 'active' => $active])
            ->getResult();

        return $result;
    }

    public function getUsersWithPlayConfigsActive()
    {
        $result = $this->getEntityManager()
            ->createQuery(
                'SELECT p'
                . ' FROM ' . $this->getEntityName() . ' p INNER JOIN p.user u'
                . ' WHERE p.active = 1 GROUP BY u.id')
            ->getResult();
        return $result;
    }


    public function getTotalByUserAndPlayForNextDraw( $userId , \DateTime $dateNextDraw )
    {
        $drawDay = new DrawDays(date('w', $dateNextDraw->getTimestamp()));
        $result = $this->getEntityManager()
            ->createQuery(
                'SELECT COUNT(p.id) '
                . ' FROM ' . $this->getEntityName() . ' p'
                . ' WHERE p.days.days LIKE :draw_days '
                . ' AND p.user = :user_id '
                . ' AND p.active = 1 '
                . ' AND :day BETWEEN p.startDrawDate and p.lastDrawDate ')
            ->setMaxResults(1)
            ->setParameters([
                'user_id' => $userId,
                'day' => $dateNextDraw,
                'draw_days' => '%'.$drawDay->value().'%'
            ])
            ->getResult();

        return (int) $result[0][1];
    }

}