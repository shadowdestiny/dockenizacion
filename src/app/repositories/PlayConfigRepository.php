<?php


namespace EuroMillions\repositories;


use EuroMillions\vo\UserId;

class PlayConfigRepository extends RepositoryBase
{

    public function getPlayConfigsActivesByUser(UserId $userId)
    {
        return $this->getPlayConfigsByUser($userId, '1');
    }

    public function getPlayConfigsInActivesByUser(UserId $userId)
    {
        return $this->getPlayConfigsByUser($userId, '0');
    }

    public function getPlayConfigsByDrawDayAndDate($day)
    {
        $result = $this->getEntityManager()
            ->createQuery(
                'SELECT p'
                . ' FROM ' . $this->getEntityName() . ' p'
                . ' WHERE p.active = 1 AND ' . $day . ' BETWEEN p.start_draw_date and p.last_draw_date '
                . ' GROUP BY p.user ' )
            ->getResult();
        return $result;
    }


    public function getPlayConfigsByUserAndDate(UserId $userId, $date)
    {
        $result = $this->getEntityManager()
            ->createQuery(
                'SELECT p'
                . ' FROM ' . $this->getEntityName() . ' p'
                . ' WHERE p.user = :user_id AND p.active = 1 AND ' . $date . ' BETWEEN p.start_draw_date and p.last_draw_date ')
            ->setParameters(['user_id' => $userId->id()])
            ->getResult();

        return $result;
    }


    /**
     * @param UserId $userId
     * @param $active
     * @return array
     */
    protected function getPlayConfigsByUser(UserId $userId, $active)
    {
        $result = $this->getEntityManager()
            ->createQuery(
                'SELECT p'
                . ' FROM ' . $this->getEntityName() . ' p'
                . ' WHERE p.user = :user_id AND p.active = ' . $active . '')
            ->setParameters(['user_id' => $userId->id()])
            ->getResult();

        return $result;
    }


}