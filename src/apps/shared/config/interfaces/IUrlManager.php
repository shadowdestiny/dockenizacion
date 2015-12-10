<?php
namespace EuroMillions\shared\config\interfaces;

interface IUrlManager
{
    public function get($uri);
    public function getStatic($uri);
}