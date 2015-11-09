<?php


namespace EuroMillions\shareconfig\interfaces;


interface ISession
{
    public function get($index);
    public function set($index, $value);
    public function has($index);
    public function destroy();
}