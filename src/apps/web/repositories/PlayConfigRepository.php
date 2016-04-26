<?php
namespace EuroMillions\web\repositories;


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
                'SELECT p'
                . ' FROM ' . $this->getEntityName() . ' p'
                . ' WHERE p.user = :user_id AND p.active = :active '
                . ' GROUP BY p.line.regular_number_one,'
                . ' p.line.regular_number_two,p.line.regular_number_three, '
                . ' p.line.regular_number_four,p.line.regular_number_five, '
                . ' p.line.lucky_number_one, p.line.lucky_number_two '
                . ' ORDER BY p.startDrawDate DESC')
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
                . ' WHERE p.active = 1 GROUP BY u.line_regular_number_one, '
                . ' u.line_regular_number_two,u.line_regular_number_three, '
                . ' u.line_regular_number_four,u.line_regular_number_five, '
                . ' u.line_lucky_number_one, u.line_lucky_number_two')
            ->getResult();
        return $result;
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

}