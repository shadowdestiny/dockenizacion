<?php


namespace EuroMillions\repositories;


use Doctrine\ORM\EntityRepository;
use EuroMillions\entities\PaymentMethod;
use Symfony\Component\Config\Definition\Exception\Exception;

class PaymentMethodRepository extends EntityRepository
{

    public function add(PaymentMethod $paymentMethod)
    {
        $this->getEntityManager()->persist($paymentMethod);
    }

}