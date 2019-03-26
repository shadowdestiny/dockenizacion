<?php
namespace EuroMillions\web\controllers\ajax;

use EuroMillions\web\controllers\CartController;
use EuroMillions\web\entities\Lottery;
use EuroMillions\web\entities\PlayConfig;
use EuroMillions\shared\helpers\SiteHelpers;
use EuroMillions\web\services\factories\OrderFactory;
use EuroMillions\web\vo\Discount;
use EuroMillions\web\vo\dto\WithDrawFundsOrderProviderDTO;
use EuroMillions\web\vo\enum\PaymentSelectorType;
use Money\Currency;
use Money\Money;

class WithdrawController extends CartController
{
    public function orderAction()
    {
        $current_user_id = $this->authService->getCurrentUser()->getId();
        $user = $this->userService->getUser($current_user_id);

        $playconfig=new PlayConfig();
        $playconfig->setFrequency(1);
        $playconfig->setUser($user);

        $money=new Money(0, new Currency('EUR'));
        $amount=new Money(intval($this->request->getPost('amount')), new Currency('EUR'));

        $withdrawLottery = new Lottery();
        $withdrawLottery->initialize([
            'id'               => 1,
            'name'             => 'Withdraw',
            'active'           => 1,
            'frequency'        => 'freq',
            'draw_time'        => 'draw',
            'single_bet_price' => new Money(23500, new Currency('EUR')),
        ]);

        $order=OrderFactory::create([$playconfig], $amount, $money, $money, new Discount(0, []), $withdrawLottery, 'Withdraw', false);

        $orderDataToPaymentProvider = new WithDrawFundsOrderProviderDTO(
            [
            'user' => $user,
            'total' => $this->request->getPost('amount'),
            'currency' => 'EUR',
            'lottery' => '',
            'isWallet' => false,
            'isMobile' => SiteHelpers::detectDevice(),
            'transactionId' => $order->getTransactionId(),
        ],
            $this->di->get('config')
        );

        //TODO: This will not work with providers at this moment. We process manually via BO.
        $this->paymentProviderService->setEventsManager($this->eventsManager);
        $this->eventsManager->attach('orderservice', $this->orderService);
        $this->cartPaymentProvider = $this->paymentProviderService->createCollectionFromTypeAndCountry(PaymentSelectorType::CREDIT_CARD_METHOD, $this->paymentCountry);
        // TODO: Select $cardPaymentProvider ( withdrawal provider in this case; based on the the last transactions of the user)
        //
        $cashierViewDTO = $this->paymentProviderService->cashier($this->cartPaymentProvider->getIterator()->current()->get(), $orderDataToPaymentProvider);
        $this->paymentProviderService->createOrUpdateDepositTransactionWithPendingStatus($order, $this->userService->getUser($user->getId()), $amount);

        $this->noRender();

        echo json_encode(
            [
                'cashier' => $cashierViewDTO
            ]
        );
    }
}
