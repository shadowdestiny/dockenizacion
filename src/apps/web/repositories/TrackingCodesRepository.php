<?php


namespace EuroMillions\web\repositories;


use Doctrine\ORM\Query\ResultSetMapping;

class TrackingCodesRepository extends RepositoryBase
{
    public function getAllTrackingCodesWithUsersCount()
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id','id');
        $rsm->addScalarResult('name','name');
        $rsm->addScalarResult('description','description');
        $rsm->addScalarResult('num_users', 'num_users');
        return  $this->getEntityManager()
            ->createNativeQuery(
                'select tc.id, tc.name, tc.description, count(u.id) as num_users
                from trackingCodes tc
                left join tc_users_list u on tc.id = u.trackingCode_id
                group by tc.id;', $rsm)->getResult();
    }
}