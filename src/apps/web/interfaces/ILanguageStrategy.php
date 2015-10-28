<?php
namespace EuroMillions\web\interfaces;

interface ILanguageStrategy
{
    public function get();
    public function set($language);
}