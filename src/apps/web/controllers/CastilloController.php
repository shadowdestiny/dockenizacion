<?php

namespace EuroMillions\web\controllers;

use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\components\tags\MetaDescriptionTag;
use EuroMillions\web\entities\User;
use EuroMillions\web\forms\CreditCardForm;
use EuroMillions\web\vo\CardHolderName;
use EuroMillions\web\vo\CardNumber;
use EuroMillions\web\vo\CreditCard;
use EuroMillions\web\vo\CVV;
use EuroMillions\web\vo\ExpiryDate;
use Money\Currency;
use Money\Money;

class CastilloController extends PublicSiteControllerBase
{
    public function indexAction()
    {

    }

    public function christmasXmlAction()
    {
        $this->christmasService->insertStockXML($this->request->getPost());
    }

}
