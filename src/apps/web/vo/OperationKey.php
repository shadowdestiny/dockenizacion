<?php


namespace EuroMillions\web\vo;


use EuroMillions\web\interfaces\ICypherStrategy;
use EuroMillions\web\vo\base\StringLiteral;

class OperationKey extends StringLiteral
{

    private $cypher;

    public function __construct($value, ICypherStrategy $cypher)
    {
        $this->cypher = $cypher;
        $this->cypher->encrypt($value);
    }

    public function getCypherResult()
    {
        return $this->cypher->getCypherResult();
    }

}