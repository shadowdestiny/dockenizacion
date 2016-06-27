<?php


namespace EuroMillions\web\vo;


use EuroMillions\web\components\ParamSignerEmPay;

class EmPayCypher
{

    protected $cypher;
    protected $params;
    protected $config;
    protected $md5Key;

    public function __construct(array $params, array $config, $md5Key)
    {
        $this->cypher = new ParamSignerEmPay();
        $this->md5Key = $md5Key;
        $this->params = $params;
        $this->config = $config;
        $this->assignParams();
    }


    private function assignParams()
    {
        $this->cypher->setSecret($this->md5Key);
        foreach($this->params as $k => $param) {
            $this->cypher->setParam($k,$param);
        }
        foreach($this->config as $k => $config) {
            $this->cypher->setParam($k, $config);
        }
    }

    public function getQueryString()
    {
        return $this->cypher->getQueryString();
    }

    public static function paramAuthenticate(array $params, $secretKey)
    {
        return ParamSignerEmPay::paramAuthenticate($params,$secretKey);
    }



}