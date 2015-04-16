//EuroMillions.namespace('EuroMillions.LotteryTickets');

function StandardLotteryTicket()
{
    this.ticketId = '';
    this.minNumber = 1;
    this.maxNumber = 50;
    this.maxChoice = 5;
    this.bonusChoice = 2;
    this.choosenNumbers = [];
    this.choosenBonusNumbers = [];
    this.ticketPrice = 6;
    this.currency = '&#8364;';
}
function SevenNumbersLotteryTicket() {
    this.ticketId = '';
    this.minNumber = 1;
    this.maxNumber = 50;
    this.maxChoice = 7;
    this.extraChoice = 2;
    this.choosenNumbers = [];
    this.choosenBonusNumbers = [];
    this.ticketPrice = 126;
    this.currency = '&#8364;';
}
function EightNumbersLotteryTicket() {
    this.ticketId = '';
    this.minNumber = 1;
    this.maxNumber = 50;
    this.maxChoice = 8;
    this.bonusChoice = 2;
    this.choosenNumbers = [];
    this.choosenBonusNumbers = [];
    this.ticketPrice = 336;
    this.currency = '&#8364;';
}
function NineNumbersLotteryTicket() {
    this.ticketId = '';
    this.minNumber = 1;
    this.maxNumber = 50;
    this.maxChoice = 9;
    this.bonusChoice = 2;
    this.choosenNumbers = [];
    this.choosenBonusNumbers = [];
    this.ticketPrice = 756;
    this.currency = '&#8364;';
}

//EuroMillions.namespace('EuroMillions.LotteryCart');
//EuroMillions.LotteryCart =
//{
//    cart: []
//};


EuroMillions.namespace("EuroMillions.Core");
EuroMillions.Core.Random = new function () {
    this.Get = function (x) {
        if (arguments[1]) {
            return Math.round(x + Math.random() * (arguments[1] - x));
        }
        else {
            return Math.floor(x * (Math.random() % 1));
        }
    }; 
    this.GetString = function (length) {
        var chars = arguments[1] || "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
        var randomstring = String.Empty;
        for (var i = 0; i < length; i++) {
            var rnum = EuroMillions.Core.Random.Get(0, chars.length);
            randomstring += chars.substring(rnum, rnum + 1);
        }
        return randomstring;

    }
};
