<?php


namespace EuroMillions\web\services\card_payment_providers\payments_util;


class PaymentsRegistry
{

    private $config;
    /** @var  PaymentsCollection */
    private $paymentsCollection;

    public function __construct(array $config)
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
        $this->paymentsCollection = $this->getPaymentsCollection();
        $di = \Phalcon\Di::getDefault();
        if( !empty( $this->paymentsCollection ) ) {
            foreach( array_values( $this->paymentsCollection->getAll() ) as $k => $payments ) {
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

    public function getCount() {
        return $this->paymentsCollection->count();
    }

}