<?php
namespace EuroMillions\web\services;

use Doctrine\ORM\EntityManager;
use EuroMillions\web\repositories\CurrencyRepository;
use EuroMillions\shared\vo\results\ActionResult;
use Money\Currency;

class CurrencyService
{
    /** @var CurrencyRepository */
    private $currencyRepository;

    public function __construct(EntityManager $entityManager)
    {
        $this->currencyRepository = $entityManager->getRepository('EuroMillions\web\entities\Currency');
    }

    public function getActiveCurrenciesCodeAndNames()
    {
        /** @var Currency $collection */
        $collection = $this->currencyRepository->findBy([],[ 'code' => 'ASC']);
        if(null !==$collection) {
            return new ActionResult(true,$collection);
        } else {
            return new ActionResult(false);
        }
    }


    public function getCurrenciesMostImportant($limit = 6)
    {
        $collection = $this->currencyRepository->findBy([],['order' => 'ASC'],$limit);
        if(count($collection)) {
            return new ActionResult(true,$collection);
        } else {
            return new ActionResult(false);
        }
    }

}