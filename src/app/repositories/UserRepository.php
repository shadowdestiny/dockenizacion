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
}