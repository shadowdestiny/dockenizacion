<?php


namespace EuroMillions\web\vo\dto;


class SiteConfigDTO
{

    public $fee_limit;
    public $fee;

    public function __construct( $fee_limit, $fee )
    {
        $this->fee_limit = $fee_limit;
        $this->fee = $fee;
    }

}