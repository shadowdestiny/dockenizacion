<?php


namespace EuroMillions\tests\helpers\builders;


use EuroMillions\tests\helpers\mothers\EuroMillionsDrawMother;
use EuroMillions\tests\helpers\mothers\LotteryMother;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\entities\Lottery;
use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\vo\CreditCardCharge;
use EuroMillions\web\vo\Order;
use Money\Currency;
use Money\Money;
use EuroMillions\tests\helpers\mothers\UserMother;

class OrderBuilder
{

    const DEFAULT_FEE = 35;
    const DEFAULT_FEE_LIMIT_VALUE = 1200;
    const DEFAULT_SINGLE_BET_PRICE = 300;

    const DEFAULT_JSON_PLAY = '{"play_config":[{"drawDays":"2","startDrawDate":"16 Feb 2016","lastDrawDate":"2016-02-16 00:00:00","frequency":"1","amount":null,"regular_numbers":null,"lucky_numbers":null,"euroMillionsLines":{"bets":[{"regular":[16,18,20,21,32],"lucky":[4,8]}]},"numbers":null,"threshold":null,"num_weeks":0},{"drawDays":"2","startDrawDate":"16 Feb 2016","lastDrawDate":"2016-02-16 00:00:00","frequency":"1","amount":null,"regular_numbers":null,"lucky_numbers":null,"euroMillionsLines":{"bets":[{"regular":[3,22,23,30,44],"lucky":[7,9]}]},"numbers":null,"threshold":null,"num_weeks":0},{"drawDays":"2","startDrawDate":"16 Feb 2016","lastDrawDate":"2016-02-16 00:00:00","frequency":"1","amount":null,"regular_numbers":null,"lucky_numbers":null,"euroMillionsLines":{"bets":[{"regular":[31,37,39,44,47],"lucky":[4,10]}]},"numbers":null,"threshold":null,"num_weeks":0},{"drawDays":"2","startDrawDate":"16 Feb 2016","lastDrawDate":"2016-02-16 00:00:00","frequency":"1","amount":null,"regular_numbers":null,"lucky_numbers":null,"euroMillionsLines":{"bets":[{"regular":[25,31,33,38,47],"lucky":[2,6]}]},"numbers":null,"threshold":null,"num_weeks":0}]}';

    protected $playConfig;
    /** @var  Money $fee */
    protected $fee;
    /** @var  Money $fee_limit_value */
    protected $fee_limit_value;
    /** @var  Money $single_bet_price */
    protected $single_bet_price;
    /** @var Lottery $lottery */
    protected $lottery;
    /** @var EuroMillionsDraw $draw */
    protected $draw;

    protected $isCheckedWalletBalance;



    public function __construct()
    {
        $this->playConfig = $this->getPlayConfig();
        $this->lottery = $this->getLottery();
        $this->draw = $this->getDraw();
        $this->hasSubscription = false;
        $this->isCheckedWalletBalance = false;
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

    public function withPlayConfigWithIds()
    {
        $this->playConfig = $this->getPlayConfigWithIds();
        return $this;
    }

    public function withSubscription()
    {
        $this->playConfig = $this->getPlayConfigWithSubscription();
        return $this;
    }

    public function withCheckedWalletBalance()
    {
        $this->isCheckedWalletBalance = true;
        return $this;
    }

    private function getLottery()
    {
        return $lottery = LotteryMother::anEuroMillions();
    }

    private function getDraw()
    {
        $draw = EuroMillionsDrawMother::anEuroMillionsDrawWithJackpotAndBreakDown()->build();
        return $draw;
    }

    private function getPlayConfig()
    {
        $user = UserMother::aUserWith50Eur()->build();
        $form_decode = json_decode(self::DEFAULT_JSON_PLAY);
        $bets = [];
        foreach($form_decode->play_config as $bet) {
            $playConfig = new PlayConfig();
            $playConfig->formToEntity($user,$bet,$bet->euroMillionsLines);
            $bets[] = $playConfig;
        }
        return $bets;
    }

    private function getPlayConfigWithSubscription()
    {
        $user = UserMother::aUserWith50Eur()->build();
        $form_decode = json_decode(self::DEFAULT_JSON_PLAY);
        $bets = [];
        foreach($form_decode->play_config as $bet) {
            $playConfig = new PlayConfig();
            $playConfig->formToEntity($user,$bet,$bet->euroMillionsLines);
            $playConfig->setFrequency(4);
            $playConfig->setLottery(LotteryMother::anEuroMillions());
            $bets[] = $playConfig;
        }
        return $bets;
    }

    private function getPlayConfigWithIds()
    {
        $user = UserMother::aUserWith50Eur()->build();
        $form_decode = json_decode(self::DEFAULT_JSON_PLAY);
        $bets = [];
        foreach($form_decode->play_config as $bet) {
            $playConfig = new PlayConfig();
            $playConfig->formToEntity($user,$bet,$bet->euroMillionsLines);
            $bets[] = $playConfig;
            $playConfig->setId(rand(1,9000));
        }
        return $bets;

    }

    /**
     * @return Order
     */
    public function build()
    {
        $order = new Order($this->playConfig, $this->single_bet_price, $this->fee, $this->fee_limit_value);
        return $order;
    }


    public function buildANewWay()
    {
        $order = new Order($this->playConfig, $this->single_bet_price, $this->fee, $this->fee_limit_value,null, false,$this->lottery, $this->draw);
        return $order;
    }

    public function buildWithWallet()
    {
        $order = new Order($this->playConfig, $this->single_bet_price, $this->fee, $this->fee_limit_value,null, true,$this->lottery, $this->draw);
        return $order;
    }



}