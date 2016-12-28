<?php


namespace EuroMillions\web\vo\dto;


class SiteConfigDTO
{

    public $feeLimit;
    public $fee;
    public $feeLimitConverted;
    public $feeConverted;
    public function __construct( $feeLimit, $fee, $feeLimitConverted, $feeConverted)
    {
        $this->feeLimit = $feeLimit;
        $this->fee = $fee;
        $this->feeLimitConverted = $feeLimitConverted;
        $this->feeConverted = $feeConverted;
    }
}