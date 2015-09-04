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
     * @param string $email
     * @return User
     */
    public function getByEmail($email)
    {
        $entity_name = $this->getEntityName();
        $result = $this->getEntityManager()
            ->createQuery(
                "SELECT u FROM {$entity_name} u WHERE u.email.value = :email"
            )
            ->setMaxResults(1)
            ->setParameters(['email' => $email])
            ->getResult();
        return $result ? $result[0] : null;
    }


    /**
     * @param string $email
     * @return User
     */
    public function getByToken($token)
    {
        $entity_name = $this->getEntityName();
        $result = $this->getEntityManager()
            ->createQuery(
                "SELECT u FROM {$entity_name} u WHERE u.validationToken.value = :token"
            )
            ->setMaxResults(1)
            ->setParameters(['token' => $token])
            ->getResult();
        return $result ? $result[0] : null;
    }
}