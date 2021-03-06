<?php
namespace EuroMillions\web\controllers\ajax;

use EuroMillions\web\controllers\CartController;
use EuroMillions\web\entities\Lottery;
use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\vo\dto\DepositPaymentProviderDTO;
use EuroMillions\shared\helpers\SiteHelpers;
use EuroMillions\web\services\factories\OrderFactory;
use EuroMillions\web\vo\Discount;
use Money\Currency;
use Money\Money;

class FundsController extends CartController
{
    public function orderAction()
    {
        $current_user_id = $this->authService->getCurrentUser()->getId();
        $user = $this->userService->getUser($current_user_id);

        $playconfig = new PlayConfig();
        $playconfig->setFrequency(1);
        $playconfig->setUser($user);

        $money = new Money(0, new Currency('EUR'));
        $amount = new Money(intval($this->request->getPost('amount')), new Currency('EUR'));

        $depositLottery = new Lottery();
        $depositLottery->initialize([
            'id' => 1,
            'name' => 'Deposit',
            'active' => 1,
            'frequency' => 'freq',
            'draw_time' => 'draw',
            'single_bet_price' => new Money(23500, new Currency('EUR')),
        ]);
        $order = OrderFactory::create([$playconfig], $amount, $money, $money, new Discount(0, []), $depositLottery, 'Deposit', false);



        $orderDataToPaymentProvider = new DepositPaymentProviderDTO(
            [
            'user' => $user,
            'total' => $this->request->getPost('amount'),
            'currency' => 'EUR',
            'lottery' => '',
            'isWallet' => false,
            'isMobile' => SiteHelpers::detectDevice()
        ],
            $this->di->get('config')
        );

        $cashierViewDTO = $this->paymentProviderService->getCashierViewDTOFromMoneyMatrix($this->cartPaymentProvider, $orderDataToPaymentProvider);

        $this->paymentProviderService->createOrUpdateDepositTransactionWithPendingStatus($order, $this->userService->getUser($user->getId()), $amount, $this->cartPaymentProvider->getName());

        $this->noRender();

        echo json_encode(
            [
                'cashier' => $cashierViewDTO
            ]
        );
    }
}
