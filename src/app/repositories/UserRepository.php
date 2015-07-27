<?php
namespace EuroMillions\repositories;

use Doctrine\ORM\EntityRepository;
use EuroMillions\entities\User;
use EuroMillions\vo\UserId;

class UserRepository extends EntityRepository
{
    /**
     * @return UserId
     */
    public function nextIdentity()
    {
        return UserId::create();
    }

    public function add(User $anUser)
    {
        $this->getEntityManager()->persist($anUser);
    }

    /**
     * @param string $username
     * @return User
     */
    public function getByUsername($username)
    {
        $entity_name = $this->getEntityName();
        $result = $this->getEntityManager()
            ->createQuery(
                "SELECT u FROM {$entity_name} u WHERE u.username.username = :username"
            )
            ->setMaxResults(1)
            ->setParameters(['username' => $username])
            ->getResult();
        return $result[0];
    }
}