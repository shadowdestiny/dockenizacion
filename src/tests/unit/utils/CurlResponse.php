<?php
namespace EuroMillions\tests\unit\utils;

class CurlResponse
{
    public $body;
    public function __construct($content)
    {
        $this->body = $content;
    }
}