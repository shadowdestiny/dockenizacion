<?php


namespace EuroMillions\web\services\card_payment_providers\royalpay\dto;


use EuroMillions\web\services\card_payment_providers\shared\dto\PaymentBodyResponse;

class RoyalPayBodyResponse extends PaymentBodyResponse
{

    protected $status;

    protected $statusMessage;

    protected $message;

    protected $metadata;



    public function __construct(\stdClass $body, $headerMessage)
    {
        $this->status = $body->status === "created";
        $this->statusMessage = !empty($headerMessage) ? $headerMessage : "";
        $this->message = !empty($body->message) ? $body->message : "";
        $this->metadata = [
            "url"             => !empty($body->cashierUrl) ? $body->cashierUrl : "",
            "redirect_url"    => !empty($body->redirect_url) ? $body->redirect_url : "",
            "redirect_method" => !empty($body->redirect_method) ? $body->redirect_method : "",
            "redirect_params" => $this->fillParams($body->redirect_params)
        ];
    }


    public static function create(\stdClass $body, $headerMessage)
    {
        return new self($body, $headerMessage);
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getStatusMessage()
    {
        return $this->statusMessage;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return array
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    private function fillParams($redirectParams)
    {
        $newArr=[];
        if(is_object($redirectParams)) {
            foreach($redirectParams as $k => $v) {
                $newArr[$k] = $v;
            }
        }
        return $newArr;
    }


}