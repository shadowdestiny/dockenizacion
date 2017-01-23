<?php


namespace EuroMillions\web\repositories;


use Doctrine\ORM\Query\ResultSetMapping;

class TcUsersListRepository extends RepositoryBase
{
    public function getUsersListByTrackingCode($id)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id','id');
        $rsm->addScalarResult('user_id','user_id');
        $rsm->addScalarResult('trackingCode_id','trackingCode_id');
        return  $this->getEntityManager()
            ->createNativeQuery(
                "SELECT tul.id, tul.user_id, tul.trackingCode_id
                FROM tc_users_list tul
                WHERE tul.trackingCode_id = '" . $id . "'", $rsm)->getResult();
    }
}