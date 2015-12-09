<?php
namespace EuroMillions\shared\shareconfig\interfaces;

interface IUrlManager
{
    public function get($uri);
    public function getStatic($uri);
}