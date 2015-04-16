EuroMillions.namespace('EuroMillions.Ticket');

EuroMillions.Ticket.Manager = Class.extend({
    options:
    {
        numberElementSelector: 'div.number',
        numberContainerSelector: 'div.standardNumbers div.number',
        numberSelector: 'div.standardNumbers a',
        selectedClass: 'clicked',
        quickPickSelector: 'button.quickPick',
        bonusNumbersContainerSelector: 'div.bonusNumbers div.number',
        lotteryTicket: {},
        clearLinesSelector: 'a.clearLines',
        ticketId: ''
    },
    init: function (options, element) {
        this.element = $(element);
        this.options = $.extend({}, this.options, options);
        this.setupEvents();
    },
    highlightNumbers: function () {
        $(this.options.numberContainerSelector, this.element).removeClass(this.options.selectedClass);
        $(this.options.bonusNumbersContainerSelector, this.element).removeClass(this.options.selectedClass);
        for (i = 0; i < this.options.lotteryTicket.choosenNumbers.length; i++) {
            $(this.options.numberContainerSelector + ".number" + this.options.lotteryTicket.choosenNumbers[i].toString(), this.element).addClass(this.options.selectedClass);
        }

        for (i = 0; i < this.options.lotteryTicket.choosenBonusNumbers.length; i++) {
            $(this.options.bonusNumbersContainerSelector + ".number" + this.options.lotteryTicket.choosenBonusNumbers[i].toString(), this.element).addClass(this.options.selectedClass);
        }

    },
    clearLines: function () {
        this.options.lotteryTicket.choosenNumbers = [];
        this.options.lotteryTicket.choosenBonusNumbers = [];
        this.highlightNumbers();
    },
    quickPick: function () {
        var minNumber = this.options.lotteryTicket.minNumber;
        var maxNumber = this.options.lotteryTicket.maxNumber;
        this.options.lotteryTicket.choosenNumbers = [];
        this.options.lotteryTicket.choosenBonusNumbers = [];

        //Generate Random Numbers for Standard Selection Numbers
        for (i = 0; i < this.options.lotteryTicket.maxChoice; i++) {
            do {
                var numberGenerated = EuroMillions.Core.Random.Get(minNumber, maxNumber);
            }
            while ($.inArray(numberGenerated, this.options.lotteryTicket.choosenNumbers) > -1);

            this.options.lotteryTicket.choosenNumbers.push(numberGenerated);
        }

        //Generate Random Numbers for Bonus Selection Numbers
        for (i = 0; i < 2; i++) {
            do {
                var numberGenerated = EuroMillions.Core.Random.Get(minNumber, 11);
            }
            while ($.inArray(numberGenerated, this.options.lotteryTicket.choosenBonusNumbers) > -1);

            this.options.lotteryTicket.choosenBonusNumbers.push(numberGenerated);
        }

        this.highlightNumbers();
        this.options.lotteryTicket.ticketId = this.options.ticketId;
    },
    //this.options.lotteryTicket.choosenNumbers
    addNumberToTicket: function (number, bonusNumbers) {
        if (bonusNumbers) {
            if (this.options.lotteryTicket.choosenBonusNumbers.length < 2) {
                this.options.lotteryTicket.choosenBonusNumbers.push(number);
                return true;
            }
        }
        else {
            if (this.options.lotteryTicket.choosenNumbers.length < this.options.lotteryTicket.maxChoice) {
                this.options.lotteryTicket.choosenNumbers.push(number);
                return true;
            }
        }
        return false;
    },
    removeNumberFromTicket: function (number, bonusNumbers) {
        if (bonusNumbers) {
            this.options.lotteryTicket.choosenBonusNumbers = $.grep(this.options.lotteryTicket.choosenBonusNumbers, function (value) {
                return value != number;
            });
        }
        else {
            this.options.lotteryTicket.choosenNumbers = $.grep(this.options.lotteryTicket.choosenNumbers, function (value) {
                return value != number;
            });
        }
    },
    checkIfTicketIsFullySelected: function () {
        if ((this.options.lotteryTicket.choosenNumbers.length == this.options.lotteryTicket.maxChoice)
            &&
            (this.options.lotteryTicket.choosenBonusNumbers.length == 2)) {
            EuroMillions.CartManager.AddTicketToCart(this.options.lotteryTicket);
        }
        else {
            EuroMillions.CartManager.DeleteByTicketId(this.options.lotteryTicket.ticketId);
        }
    },
    setupEvents: function () {
        var options = this.options;
        var _this = this;

        //Event is triggered when clicked on the number element STANDARD NUMBERS
        $(options.numberContainerSelector, this.element).click(function () {
            if ($(this).hasClass(options.selectedClass)) {
                $(this).removeClass(options.selectedClass);
                _this.removeNumberFromTicket($(this).text(), false);
            }
            else {
                if (_this.addNumberToTicket($(this).text(), false)) {
                    $(this).addClass(options.selectedClass);
                }
            }
            _this.checkIfTicketIsFullySelected();
        });

        //Event is triggered when
        $(options.numberSelector, this.element).click(function (e) {
            $(this).parent().parent().find(options.numberElementSelector).removeClass(options.selectedClass);
            e.preventDefault();
        });

        //GENERAL QUICK PICK EVENT
        $(options.quickPickSelector, this.element).click(function () {
            _this.quickPick();
            EuroMillions.CartManager.AddTicketToCart(_this.options.lotteryTicket);
        });


        //Event is triggered when clicked on the number element BONUS NUMBERS
        $(options.bonusNumbersContainerSelector, this.element).click(function () {
            if ($(this).hasClass(options.selectedClass)) {
                $(this).removeClass(options.selectedClass);
                _this.removeNumberFromTicket($(this).text(), true);
            }
            else {
                if (_this.addNumberToTicket($(this).text(), true)) {
                    $(this).addClass(options.selectedClass);
                }
            }
            _this.checkIfTicketIsFullySelected();
        });
        //Clear Lines Event
        $(options.clearLinesSelector, this.element).click(function (e) {
            e.preventDefault();
            _this.clearLines();
            EuroMillions.CartManager.DeleteByTicketId($(this).parent().parent().attr("id"));
        });
    }
});

$.plugin('ticketManager', EuroMillions.Ticket.Manager);


$(document).ready(function () {
    $("#PlayNow").click(function () {
        EuroMillions.CartManager.RedirectToCongaLotto();
    });

});