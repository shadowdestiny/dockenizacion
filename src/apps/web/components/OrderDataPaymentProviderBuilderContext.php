<?php


namespace EuroMillions\web\components;

use EuroMillions\shared\enums\PaymentProviderEnum;
use EuroMillions\web\interfaces\IHandlerPaymentGateway;
use EuroMillions\web\vo\dto\order_payment_provider\deposit\MoneyMatrixDepositOrderPaymentProviderDTO;
use EuroMillions\web\vo\dto\order_payment_provider\withdraw\MoneyMatrixWithdrawOrderPaymentProviderDTO;
use EuroMillions\web\vo\dto\UserDTO;
use EuroMillions\web\vo\enum\OrderType;
use EuroMillions\web\vo\Order;
use Phalcon\Config;

class OrderDataPaymentProviderBuilderContext
{
    private $orderDataPaymentProviderDTO;

    private $mapperDeposit = [
        PaymentProviderEnum::MONEYMATRIX => 'EuroMillions\web\vo\dto\order_payment_provider\deposit\MoneyMatrixDepositOrderPaymentProviderDTO',
        PaymentProviderEnum::ROYALPAY => 'EuroMillions\web\vo\dto\order_payment_provider\deposit\DepositOrderPaymentProviderDTO',
        PaymentProviderEnum::WIRECARD => 'EuroMillions\web\vo\dto\order_payment_provider\deposit\DepositOrderPaymentProviderDTO',
        PaymentProviderEnum::FAKECARD => 'EuroMillions\web\vo\dto\order_payment_provider\deposit\DepositOrderPaymentProviderDTO',
    ];

    private $mapperWithdraw = [
        PaymentProviderEnum::MONEYMATRIX => 'EuroMillions\web\vo\dto\order_payment_provider\withdraw\MoneyMatrixWithdrawOrderPaymentProviderDTO',
        PaymentProviderEnum::ROYALPAY => 'EuroMillions\web\vo\dto\order_payment_provider\withdraw\WithdrawOrderPaymentProviderDTO',
        PaymentProviderEnum::WIRECARD => 'EuroMillions\web\vo\dto\order_payment_provider\withdraw\WithdrawOrderPaymentProviderDTO',
        PaymentProviderEnum::FAKECARD => 'EuroMillions\web\vo\dto\order_payment_provider\withdraw\WithdrawOrderPaymentProviderDTO',
    ];

    public function __construct(IHandlerPaymentGateway $paymentMethod, UserDTO $userDto, Order $order, array $data, Config $config)
    {
        if($order->getOrderType() == OrderType::DEPOSIT){
            if(isset($this->mapperDeposit[$paymentMethod->getName()])) {
                $this->orderDataPaymentProviderDTO = new $this->mapperDeposit[$paymentMethod->getName()](
                    $userDto,
                    $order,
                    $data,
                    $config
                );
            }
        }
        else if($order->getOrderType() == OrderType::WINNINGS_WITHDRAW) {
            if(isset($this->mapperWithdraw[$paymentMethod->getName()])) {
                $this->orderDataPaymentProviderDTO = new $this->mapperWithdraw[$paymentMethod->getName()](
                    $userDto,
                    $order,
                    $data,
                    $config
                );
            }
        }
    }


    public function build()
    {
        return $this->orderDataPaymentProviderDTO;
    }
}
