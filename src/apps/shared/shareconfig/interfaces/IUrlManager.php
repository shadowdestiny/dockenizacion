<?php
namespace EuroMillions\shareconfig\interfaces;

interface IUrlManager
{
    public function get($uri);
    public function getStatic($uri);
}