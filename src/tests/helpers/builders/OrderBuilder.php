<?php


namespace tests\helpers\builders;


use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\vo\Order;
use Money\Currency;
use Money\Money;
use tests\helpers\mothers\UserMother;

class OrderBuilder
{

    const DEFAULT_FEE = 35;
    const DEFAULT_FEE_LIMIT_VALUE = 12000;
    const DEFAULT_SINGLE_BET_PRICE = 2500;

    const DEFAULT_JSON_PLAY = '{"drawDays":"5","startDrawDate":"05 Feb 2016","lastDrawDate":"2016-02-05 00:00:00","frequency":"1","amount":null,"regular_numbers":null,"lucky_numbers":null,"euroMillionsLines":{"bets":[{"regular":[3,8,11,16,44],"lucky":[3,5]},{"regular":[6,17,37,38,48],"lucky":[1,5]}]},"numbers":null,"threshold":null,"num_weeks":0}';

    protected $playConfig;
    /** @var  Money $fee */
    protected $fee;
    /** @var  Money $fee_limit_value */
    protected $fee_limit_value;
    /** @var  Money $single_bet_price */
    protected $single_bet_price;


    public function __construct()
    {
        $this->playConfig = $this->getPlayConfig();
        $this->fee = new Money(self::DEFAULT_FEE, new Currency('EUR'));
        $this->fee_limit_value = new Money(self::DEFAULT_FEE_LIMIT_VALUE, new Currency('EUR'));
        $this->single_bet_price = new Money(self::DEFAULT_SINGLE_BET_PRICE, new Currency('EUR'));
    }

    public static function anOrder()
    {
        return new OrderBuilder();
    }

    public function withPlayConfig(PlayConfig $playConfig)
    {
        $this->playConfig = $playConfig;
        return $this;
    }


    private function getPlayConfig()
    {
        $form_decode = json_decode(self::DEFAULT_JSON_PLAY);
        $bets = [];
        foreach($form_decode->euroMillionsLines->bets as $bet) {
            $bets[] = $bet;
        }
        $playConfig = new PlayConfig();
        $user = UserMother::aUserWith50Eur()->build();
        $playConfig->formToEntity($user, self::DEFAULT_JSON_PLAY, $bets);
        return $playConfig;
    }

    /**
     * @return Order
     */
    public function build()
    {
        $order = new Order($this->playConfig, $this->single_bet_price, $this->fee, $this->fee_limit_value);
        return $order;
    }



}