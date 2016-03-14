<?php
namespace EuroMillions\shared\interfaces;

interface IUrlManager
{
    public function get($uri);
    public function getStatic($uri);
}