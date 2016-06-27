<?php


namespace EuroMillions\web\controllers;


use EuroMillions\web\entities\GuestUser;
use EuroMillions\web\vo\EmPayCypher;
use Money\Currency;
use Money\Money;

class EmpayController extends PaymentController
{


    public function paymentAction()
    {
        $userId = $this->authService->getCurrentUser()->getId();
        $play_service = $this->domainServiceFactory->getPlayService();
        $result = $play_service->playWithEmPlay($userId);
        $this->playResult($result);
    }


    public function depositAction()
    {
        $amount = $this->request->getPost('item_1_unit_price_EUR');
        $userId = $this->authService->getCurrentUser();
        if($userId instanceof GuestUser) {
            throw new \Exception();
        }
        $walletService = $this->domainServiceFactory->getWalletService();
        $result = $walletService->payFromEmpay($userId,new Money((int) str_replace('.','',$amount) * 100,new Currency('EUR')));
        if($result->success()) {
            $this->response->redirect('/'.$this->lottery.'/account/wallet');
            return false;
        } else {
            $this->response->redirect('/'.$this->lottery.'/result/failure');
            return false;
        }

    }

    /**
     * @return array
     */
    private function validate()
    {
        $di = \Phalcon\Di\FactoryDefault::getDefault();
        $config = $di->get('config')['empay_iframe'];
        $config = (array)$config;
        $params = $this->request->get();
        parse_str($params, $arr);
        return EmPayCypher::paramAuthenticate($arr,$config['md5Key']);
    }


}