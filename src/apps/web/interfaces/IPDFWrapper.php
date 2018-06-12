<?php

namespace EuroMillions\web\interfaces;

interface IPDFWrapper
{
    public function build($header, $body, $footer);

    public function getPDF();
}
