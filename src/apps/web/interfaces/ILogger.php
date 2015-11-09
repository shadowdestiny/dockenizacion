<?php
namespace EuroMillions\web\interfaces;

interface ILogger
{
    public function begin();
    public function commit();
    public function alert($message);
    public function log($message, $level=null);
    public function error($message);
    public function info($message);
}