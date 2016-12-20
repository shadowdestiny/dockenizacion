<?php


namespace EuroMillions\web\services\card_payment_providers\payments_util;


class PaymentsRegistry
{


    private $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     *
     * @return array
     */
    public function getInstances()
    {
        $instancesCollection = [];
        $di = \Phalcon\Di::getDefault();
        if( !empty($this->getPaymentsCollection()) ) {
            foreach( array_values($this->getPaymentsCollection()->getAll() ) as $k => $payments ) {
                $config = $di->get('config')[$this->config[$k]];
                array_push($instancesCollection,new $payments($config));
            }
            return $instancesCollection;
        }
        return $instancesCollection;
    }

    protected function getPaymentsCollection()
    {
        $interface = "EuroMillions\\web\\services\\card_payment_providers\\ICreditCardStrategy";
        return new PaymentsCollection(array_filter(get_declared_classes(),
                                                   create_function('$className',
                                                       "return in_array(\"{$interface}\", class_implements(\"\$className\"));")
            )
        );
    }

}