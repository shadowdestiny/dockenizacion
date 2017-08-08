<?php

namespace EuroMillions\web\controllers;

use Phalcon\Exception;

class CastilloController extends PublicSiteControllerBase
{
    public function indexAction()
    {

    }

    public function testAction()
    {

        try {
            $ch = curl_init();

            if (FALSE === $ch)
                throw new Exception('failed to initialize');

            $xmlstream = '<?xml version="1.0" encoding="UTF-8"?><message><operation id="20170808080159303919" key="4" type="4"><content>dDziUcQOLCCSSkQwWBSO/dlnr1CXoT0dffnFFg5/040zAWg7IBJfb1nglwT9LbogBtOK83hIwR9rxrEkvXZpCyD1iBahT7K9lo6YK0vOWJuDVu7bNbuPXHVJqrHBDh9qu+COpqXmCaP3dkiMLgoCcY0KMXK/tETo9sCmLjMsoeA=</content></operation><signature>Y2Q1OGJkYmJkZGFiNGZkMTQ4NThiODliYjExNjQ5MDAwYTJlM2Q5ZQ==</signature></message>';

            curl_setopt($ch, CURLOPT_POST,1);
            curl_setopt($ch, CURLOPT_URL, 'https://beta.euromillions.com/castillo/christmasxml');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlstream);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);

            $content = curl_exec($ch);

            if (FALSE === $content)
                throw new Exception(curl_error($ch), curl_errno($ch));

            curl_close ($ch);

            echo $content;
        } catch(Exception $e) {

            trigger_error(sprintf(
                'Curl failed with error #%d: %s',
                $e->getCode(), $e->getMessage()),
                E_USER_ERROR);

        }
    }

    public function christmasXmlAction()
    {
        var_dump($this->request->get());
        var_dump($this->request->getPost());
        parse_str(file_get_contents('php://input'), $requestData);
        var_dump($requestData);

        $this->christmasService->insertStockXML($this->request->getPost());
    }

}
