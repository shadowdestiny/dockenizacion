<?php


namespace EuroMillions\repositories;


use Doctrine\ORM\EntityRepository;
use EuroMillions\entities\PaymentMethod;
use EuroMillions\entities\User;

class PaymentMethodRepository extends EntityRepository
{

    public function add(PaymentMethod $paymentMethod)
    {
        $this->getEntityManager()->persist($paymentMethod);
    }

    /**
     * @param User $user
     * @return array[\EuroMillions\entities\PaymentMethod]
     */
    public function getPaymentMethodsByUser(User $user)
    {
        $entity_name = $this->getEntityName();
        $result = $this->getEntityManager()
            ->createQuery(
                "SELECT p FROM {$entity_name} p WHERE p.user = :user"
            )
            ->setParameters(['user' => $user->getId()->id()])
            ->getResult();
        return $result ? $result : null;
    }

}