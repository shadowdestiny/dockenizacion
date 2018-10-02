<?php
namespace EuroMillions\web\controllers\ajax;

use EuroMillions\web\controllers\CartController;
use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\vo\dto\OrderPaymentProviderDTO;
use EuroMillions\web\vo\Order;
use Money\Currency;
use Money\Money;
use EuroMillions\shared\helpers\SiteHelpers;
class FundsController extends CartController
{
    public function orderAction()
    {
        $playconfig=new PlayConfig();
        $playconfig->setFrequency(1);
        $money=new Money(0, new Currency('EUR'));
        $amount=new Money(intval($this->request->getPost('amount')), new Currency('EUR'));
        $order=new Order([$playconfig], $amount, $money, $money, null, false, $this->lotteryService->getLotteryConfigByName('Euromillions'), null);

        $current_user_id = $this->authService->getCurrentUser()->getId();
        $user = $this->userService->getUser($current_user_id);

        $orderDataToPaymentProvider = new OrderPaymentProviderDTO( [
            'user' => $user,
            'total' => $this->request->getPost('amount'),
            'currency' => 'EUR',
            'lottery' => '',
            'isWallet' => false,
            'isMobile' => SiteHelpers::detectDevice()
        ],
            $this->di->get('config')
        );

        $cashierViewDTO = $this->paymentProviderService->getCashierViewDTOFromMoneyMatrix($this->cartPaymentProvider,$orderDataToPaymentProvider);

        $this->paymentProviderService->createOrUpdateDepositTransactionWithPendingStatus($order,$this->userService->getUser($user->getId()), $amount, $cashierViewDTO->transactionID);

        echo json_encode(['cashier' => $cashierViewDTO]);

    }
}