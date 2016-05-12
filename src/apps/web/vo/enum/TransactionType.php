<?php


namespace EuroMillions\web\vo\enum;


class TransactionType
{

    const TICKET_PURCHASE = 'ticket_purchase';

    const AUTOMATIC_PURCHASE = 'automatic_purchase';

    const FUNDS_ADDED = 'funds_added';

    const WINNINGS_RECEIVED = 'winnings_received';

    const WINNINGS_CONVERTED_FUNDS = 'winnings_converted_funds';

    const WINNINGS_WITHDRAW = 'winnings_withdraw';

    const REFUND = 'refund';

    const DEPOSIT = 'deposit';

    const BONUS = 'bonus';

    const BIG_WINNING = 'big_winning';


}