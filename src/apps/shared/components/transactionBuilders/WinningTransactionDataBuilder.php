<?php

namespace EuroMillions\shared\components\transactionBuilders;

use EuroMillions\shared\interfaces\IBuildTransactionData;
use EuroMillions\shared\vo\Winning;
use EuroMillions\web\entities\Bet;
use EuroMillions\web\entities\User;
use Money\Money;

class WinningTransactionDataBuilder implements IBuildTransactionData
{

    /**
     * @var Winning
     */
    private $winning;
    /**
     * @var Bet
     */
    private $bet;
    /**
     * @var Money
     */
    private $amount;
    /**
     * @var User
     */
    private $user;

    /**
     * WinningTransactionDataBuilder constructor.
     * @param Winning $winning
     * @param Bet $bet
     * @param User $user
     * @param Money $amount
     */
    public function __construct(Winning $winning, Bet $bet, User $user, Money $amount)
    {
        $this->winning = $winning;
        $this->bet = $bet;
        $this->user = $user;
        $this->amount = $amount;
    }

    /**
     *
     */
    public function generate()
    {
        if($this->winning->greaterThanOrEqualThreshold()){
            $this->user->setWinningAbove($this->winning->getPrice());
            $this->user->setShowModalWinning(1);
        }
        else{
            $this->user->awardPrize($this->winning->getPrice());


        }
    }

    /**
     * @return array
     */
    public function getData()
    {
        return [
            'draw_id' => $this->bet->getEuroMillionsDraw()->getId(),
            'bet_id' => $this->bet->getId(),
            'amount' => $this->amount->getAmount(),
            'user' => $this->user,
            'walletBefore' => $this->user->getWallet(),
            'walletAfter' => $this->user->getWallet(),
            'state' => 'pending',
            'now' => new \DateTime()
        ];
    }

    /**
     * @return bool
     */
    public function greaterThanOrEqualThreshold(){
        return $this->winning->greaterThanOrEqualThreshold();
    }
}