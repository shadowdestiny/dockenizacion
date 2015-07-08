<?php
namespace EuroMillions\repositories;

use Doctrine\ORM\EntityRepository;
use EuroMillions\entities\User;
use EuroMillions\vo\UserId;
use Rhumsaa\Uuid\Uuid;

class UserRepository extends EntityRepository
{
    public function nextIdentity()
    {
        return UserId::create(strtoupper(Uuid::uuid4()));
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