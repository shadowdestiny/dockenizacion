<?php
namespace EuroMillions\shared\interfaces;

interface IBuildTransactionData
{
    public function generate();
    public function getData();
    public function setType($type);
    public function getType();
}