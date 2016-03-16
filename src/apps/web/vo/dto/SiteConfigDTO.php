<?php


namespace EuroMillions\web\vo\dto;


class SiteConfigDTO
{

    public $feeLimit;
    public $fee;

    public function __construct( $feeLimit, $fee )
    {
        $this->feeLimit = $feeLimit;
        $this->fee = $fee;
    }

}