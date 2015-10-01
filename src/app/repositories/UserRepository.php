<?php
namespace EuroMillions\repositories;

use EuroMillions\entities\User;
use EuroMillions\vo\UserId;

class UserRepository extends RepositoryBase
{
    /**
     * @return UserId
     */
    public function nextIdentity()
    {
        return UserId::create();
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