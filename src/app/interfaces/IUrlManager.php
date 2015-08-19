<?php
namespace EuroMillions\interfaces;

interface IUrlManager
{
    public function get($uri);
    public function getStatic($uri);
}