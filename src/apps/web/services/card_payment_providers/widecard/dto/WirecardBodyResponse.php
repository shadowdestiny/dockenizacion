<?php


namespace EuroMillions\web\services\card_payment_providers\widecard\dto;


use EuroMillions\web\services\card_payment_providers\shared\dto\PaymentBodyResponse;

class WirecardBodyResponse extends PaymentBodyResponse
{

    protected $status;

    protected $statusMessage;

    protected $message;

    public function __construct(\stdClass $body, $headerMessage)
    {
        $this->status = $body->status === "ok";
        $this->statusMessage = !empty($headerMessage) ? $headerMessage : "";
        $this->message = !empty($body->message) ? $body->message : "";
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
}