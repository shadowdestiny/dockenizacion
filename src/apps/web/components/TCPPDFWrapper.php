<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 11/06/18
 * Time: 15:51
 */

namespace EuroMillions\web\components;


use EuroMillions\web\interfaces\IPDFWrapper;

class TCPPDFWrapper implements IPDFWrapper
{

    private $tcpPDF;

    private $html;

    public function __construct(\TCPDF $tcpPDF)
    {
        $this->tcpPDF = $tcpPDF;
    }

    public function getPDF()
    {
        $this->tcpPDF->AddPage();
        $this->tcpPDF->writeHTML($this->html, true, 0,0);
        $this->tcpPDF->lastPage();
        $this->tcpPDF->Output('mydata.pdf', 'D');
    }

    public function build($header, $body, $footer)
    {
        $this->html = $body;
    }
}