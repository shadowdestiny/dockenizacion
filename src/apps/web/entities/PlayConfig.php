<?php


namespace EuroMillions\web\entities;


use Doctrine\Common\Collections\ArrayCollection;
use EuroMillions\web\interfaces\IEntity;
use EuroMillions\web\interfaces\IEMForm;
use EuroMillions\web\vo\Discount;
use EuroMillions\web\vo\EuroMillionsLine;
use EuroMillions\web\vo\EuroMillionsLuckyNumber;
use EuroMillions\web\vo\EuroMillionsRegularNumber;
use Money\Money;
use Money\Currency;
use Symfony\Component\Config\Definition\Exception\Exception;


class PlayConfig extends EntityBase implements IEntity,IEMForm,\JsonSerializable
{

    protected $id;

    protected $bet;

    /** @var  User */
    protected $user;

    /** @var  EuroMillionsLine */
    protected $line;

    /** @var  \DateTime */
    protected $startDrawDate;

    /** @var  \DateTime */
    protected $lastDrawDate;

    protected $active;

    protected $frequency;

    protected $lottery;

    /** @var Discount */
    protected $discount;

    protected $hasDiscount;

    public function __construct()
    {
        $this->bet = new ArrayCollection();
    }


    public function formToEntity(User $user, $json, $bets)
    {
        $formPlay = null;
        try{
            $formPlay = $json;
            if(empty($formPlay)){
                throw new Exception('Error converting object to array from storage');
            }
            $this->setUser($user);
            $euroMillionsLine = null;
            foreach($bets as $bet) {
                $regular_numbers = [];
                $lucky_numbers = [];

                if(is_array($bet)) {
                    $regular = $bet[0]->regular;
                    $lucky = $bet[0]->lucky;
                } else {
                    $regular = $bet->regular;
                    $lucky = $bet->lucky;
                }
                foreach ($regular as $number) {
                    $regular_numbers[] = new EuroMillionsRegularNumber( (int) $number);
                }
                foreach ($lucky as $number) {
                    $lucky_numbers[] = new EuroMillionsLuckyNumber((int) $number);
                }
                $euroMillionsLine = new EuroMillionsLine($regular_numbers,$lucky_numbers);
            }
            $this->setLine($euroMillionsLine);
            $this->setActive(true);
            $this->setId(1);
            $this->setStartDrawDate(new \DateTime($formPlay->startDrawDate));
            $this->setLastDrawDate(new \DateTime($formPlay->lastDrawDate));
            $this->setFrequency((int) $formPlay->frequency);

        }catch(Exception $e){
            throw new Exception($e);
        }
    }

    public function toJsonData()
    {
        return json_encode($this, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        $lines = [];
        if( null !== $this->line) {
                $lines[] = $this->line->toJsonData();
        }

        return [
            'id' => $this->id,
            'startDrawDate' => $this->startDrawDate->format('Y-m-d H:i:s'),
            'lastDrawDate' => $this->lastDrawDate->format('Y-m-d H:i:s'),
            'frequency' => $this->frequency,
            'euromillions_line' => $lines,
            'user' => ['id' => (string) $this->user->getId()]
        ];
    }

    /**
     * @return mixed
     */
    public function getFrequency()
    {
        return $this->frequency;
    }

    /**
     * @param mixed $frequency
     */
    public function setFrequency($frequency)
    {
        $this->frequency = $frequency;
    }

    public function setId($id)
    {
        $this->id = $id;
    }


    public function getId()
    {
        return $this->id;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setLine($line)
    {
        $this->line = $line;
    }

    public function getLine()
    {
        return $this->line;
    }

    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @return bool
     */
    public function getActive()
    {
        return $this->active;
    }

    public function setStartDrawDate($startDrawDate)
    {
        $this->startDrawDate = $startDrawDate;
    }

    /**
     * @return \DateTime
     */
    public function getStartDrawDate()
    {
        return $this->startDrawDate;
    }

    public function setLastDrawDate($lastDrawDate)
    {
        $this->lastDrawDate = $lastDrawDate;
    }

    /**
     * @return \DateTime
     */
    public function getLastDrawDate()
    {
        return $this->lastDrawDate;
    }

    public function setBet($bet)
    {
        $this->bet = $bet;
    }

    public function getBet()
    {
        return $this->bet;
    }

    public function numBets()
    {
        return 1;
    }
    /**
     * @return Lottery
     */
    public function getLottery()
    {
        return $this->lottery;
    }

    public function setLottery($lottery)
    {
        $this->lottery = $lottery;
    }

    /**
     * @return Discount
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * @return mixed
     */
    public function getHasDiscount()
    {
        return $this->discount->getValue() > 1;
    }

    /**
     * @param mixed $hasDiscount
     */
    public function setHasDiscount($hasDiscount)
    {
        $this->hasDiscount = $hasDiscount;
    }

    /**
     * @param Discount $discount
     */
    public function setDiscount(Discount $discount)
    {
        $this->discount = $discount;
    }

    public function hasBet()
    {
        return count($this->getBet()) > 0;
    }

    public function getSinglePrice()
    {
        if (is_null($this->getDiscount())) {
            $discount = 1;
        } else {
            $discount = ($this->getDiscount()->getValue() / 100) +1;
        }
        $amount = new Money((int)round(($this->numBets() * ($this->getLottery()->getSingleBetPrice()->getAmount())) / $discount), new Currency('EUR'));
        return $amount;
    }

}