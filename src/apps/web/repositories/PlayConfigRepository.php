<?php


namespace EuroMillions\web\repositories;


use EuroMillions\web\vo\UserId;

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

    /**
     * @return array
     */
    public function getPlayConfigsByDrawDayAndDate(\DateTime $day)
    {
        $result = $this->getEntityManager()
            ->createQuery(
                'SELECT p'
                . ' FROM ' . $this->getEntityName() . ' p'
                . ' WHERE p.active = 1 AND :day BETWEEN p.startDrawDate and p.lastDrawDate '
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


    public function getPlayConfigsByUserAndDate(UserId $userId, \DateTime $day)
    {
        $result = $this->getEntityManager()
            ->createQuery(
                'SELECT p'
                . ' FROM ' . $this->getEntityName() . ' p'
                . ' WHERE p.user = :user_id AND p.active = 1 AND :day BETWEEN p.startDrawDate and p.lastDrawDate ')
            ->setParameters(['user_id' => $userId->id(),'day' => $day])
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
}