<?php


namespace EuroMillions\web\components\tags;


use EuroMillions\shared\vo\results\PaymentProviderResult;
use EuroMillions\web\entities\User;
use EuroMillions\web\interfaces\ICardPaymentProvider;
use EuroMillions\web\interfaces\IPaymentResponseRedirect;
use EuroMillions\web\vo\CreditCard;
use EuroMillions\web\vo\dto\payment_provider\PaymentProviderDTO;
use EuroMillions\web\vo\EmPayCypher;
use EuroMillions\web\vo\Order;
use Money\Money;
use Phalcon\Exception;
use Phalcon\Tag;

class EPayIframeTag extends Tag implements ICardPaymentProvider
{
    protected $config;
    public static function render(array $params, array $config = null)
    {
        if(null == $config) {
            $di = \Phalcon\Di\FactoryDefault::getDefault();
            $config = $di->get('config')['empay_iframe'];
            $config = (array) $config;
        }
        if(!is_array($params)) {
            throw new Exception('Params should be an array');
        }
        if(!isset($config['md5Key'])) {
            throw new Exception('Md5Key is missed');
        }

        return self::buildUrl($params,$config);
    }


    private static function buildUrl(array $params, array $config)
    {
        $params = self::mapAndValidate($params,$config);
        $url = $config['url'];
        unset($config['url']);
        $md5Key = $config['md5Key'];
        unset($config['md5Key']);
        $cypher = new EmPayCypher($params,$config,$md5Key);
        return '<iframe width="850px" height="950px" src="'.$url.'?'.$cypher->getQueryString().'" allowfullscreen scrolling="no"
        seamless="seamless" frameborder=0></iframe>';
    }

    private static function mapAndValidate(array $params, array $config)
    {
        $validate = array_walk($params,function($k,$param){
             if(isset($param['order_reference']) && empty($param['order_reference'])) {
                 throw new Exception('Order reference should be entered');
             }
             if(isset($param['amount'])  && (empty($param['amount']) || !is_numeric((int) str_replace('.','',$param['amount'])))) {
                 throw new Exception('An amount is mandatory');
             }
             return true;
        });
        if($validate) {
            $params['form_id'] = $params['form'] == 'purchase' ? $config['form_purchase_id'] : $config['form_deposit_id'];
            unset($params['form']);
//            array_walk($params, function(&$value, &$param) use (&$result){
//                    if($param == 'form_purchase_id' || $param == 'form_deposit_id') {
//                        $result['form_id'] = $config[$param];
//                    } else {
//                        $result[$param] = $value;
//                    }
//            }, $result);
        }

        $params['item_1_code'] = 'EMTICKET';
        $params['item_1_name'] = 'Euromillions_ticket';
        $params['item_1_qty'] = '1';
        $params['form_language'] = 'EN';
        $params['item_1_predefined'] = '0';
        $params['item_1_digital'] = '1';
        return $params;
    }

    /**
     * @param PaymentProviderDTO $data
     * @return void
     * @throws \Exception
     */
    public function charge(PaymentProviderDTO $data)
    {
        throw new \Exception();
    }

    public function getName()
    {
        throw new \Exception();
    }

    /**
     * @return IPaymentResponseRedirect
     */
    public function getResponseRedirect()
    {
        // TODO: Implement getResponseRedirect() method.
    }
}