<?php
namespace EuroMillions\interfaces;

interface ILanguageStrategy
{
    public function get();
    public function set($language);
}