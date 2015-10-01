<?php


namespace EuroMillions\repositories;


use EuroMillions\vo\UserId;

class PlayConfigRepository extends RepositoryBase
{

    public function getPlayConfigsActivesByUser(UserId $userId)
    {
        $result = $this->getEntityManager()
            ->createQuery(
                'SELECT p'
                . ' FROM ' . $this->getEntityName() . ' p'
                . ' WHERE p.user = :user_id AND p.active = 1')
            ->setParameters(['user_id' => $userId->id()])
            ->getResult();

        return $result;
    }

    public function getPlayConfigsInActivesByUser(UserId $userId)
    {
        $result = $this->getEntityManager()
            ->createQuery(
                'SELECT p'
                . ' FROM ' . $this->getEntityName() . ' p'
                . ' WHERE p.user = :user_id AND p.active = 0')
            ->setParameters(['user_id' => $userId->id()])
            ->getResult();

        return $result;
    }


}