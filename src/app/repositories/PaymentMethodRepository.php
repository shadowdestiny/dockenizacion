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
        return $this->findBy([
            'user' => $user
        ]);
    }

}