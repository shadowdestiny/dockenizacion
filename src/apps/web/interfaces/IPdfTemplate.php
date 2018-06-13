<?php


namespace EuroMillions\web\interfaces;


interface IPdfTemplate
{
    public function loadHeader();

    public function loadFooter();

    public function loadBody();

}