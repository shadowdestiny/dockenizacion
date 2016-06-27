<?php


namespace EuroMillions\web\controllers;


use EuroMillions\web\vo\EmPayCypher;
use Money\Currency;
use Money\Money;

class EmpayController extends PaymentController
{


    public function paymentAction()
    {
        if($this->validate()) {
            $userId = $this->authService->getCurrentUser()->getId();
            $play_service = $this->domainServiceFactory->getPlayService();
            $result = $play_service->playWithEmPlay($userId);
            $this->playResult($result);
        }
    }


    public function depositAction()
    {
        if($this->validate()) {
            $userId = $this->authService->getCurrentUser();
            $walletService = $this->domainServiceFactory->getWalletService();
            $result = $walletService->payFromEmpay($userId,new Money((int) str_replace('.','',''),new Currency('EUR')));
            if($result->success()) {
                $this->response->redirect('/'.$this->lottery.'/account/wallet');
                return false;
            } else {
                $this->response->redirect('/'.$this->lottery.'/result/failure');
                return false;
            }
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