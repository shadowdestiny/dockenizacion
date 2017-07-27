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
        $xml = '<?xml version="1.0" encoding="UTF-8"?><message><operation id="20170720115153370170" key="3" type="4"><content>N1LslsHU+VEqL+8N0AZra+YVYKTxcTCR37+89rpxW3gOPz0FdUOq5SM4seO1i/4kopoTD79A2KiSNsRaSZWQbl34h5ZIf/XRCYQ8YJbiaEQJP763QcwBdEj4VHSys1j9VmxIOKd6l9ZTg6xBYiJ0DnBXKW7fKS2B0V+ABzPIUoM0wIUJ4+TWN8aQn65zuY+PoEAAWNNeuT/xRlfwDlmKssZI82UoZTmWoBfrA1ligBAPFZRQ2Xx+lWCyogXvLgxEBquNxXJzcu8EuX6a+KN2gb96CxaGP1zCq/GM4AArVcn5fckAmOtBRw==</content></operation><signature>OTY0NzU3MDFjMmU4N2YyMjE3MjM2MGY5ZDk4OGY3ODMyNzA1MzE0Mg==</signature></message>';
        $this->christmasService->insertStockXML($xml);
    }

}
