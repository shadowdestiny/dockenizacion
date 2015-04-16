EuroMillions.namespace('EuroMillions.CartManager');

EuroMillions.CartManager = function () {

    var Tickets = [];
    var TotalAmountSelector = 'div.boxBlackContainer';
    var CartContainer = 'div#ticketsCart';
    var renewTicketCheckBoxSelector = 'input.renewTicket';
    var RedirectLinkPrefix = "http://www.congalotto.com/playlotto.php?lot_id=8&account=translease&track=euromil&lot_nums=";
    return {
        RedirectToCongaLotto: function () {
            if (Tickets.length == 5 || Tickets.length == 10) {
                var urlGenerated = RedirectLinkPrefix;
                for (i = 0; i < Tickets.length; i++) {
                    for (x = 0; x < Tickets[i].choosenNumbers.length; x++) {
                        urlGenerated += Tickets[i].choosenNumbers[x] + ",";
                    }
                    for (x = 0; x < Tickets[i].choosenBonusNumbers.length; x++) {
                        urlGenerated += Tickets[i].choosenBonusNumbers[x] + ",";
                    }
                    urlGenerated += "|";
                }
                if ($(renewTicketCheckBoxSelector).attr("checked") == true) {
                    urlGenerated += "&renew=1";
                }
                else {
                    urlGenerated += "&renew=0";
                }
                window.location.href = urlGenerated;
            }
        },
        AddTicketToCart: function (ticket) {
            var found = false;
            if (Tickets.length > 0) {
                for (i = 0; i < Tickets.length; i++) {
                    if (Tickets[i].ticketId == ticket.ticketId) {
                        found = true;
                        console.log("found");
                        Tickets[i] = ticket;
                        this.UpdateCart();
                    }
                }
                if (!found) {
                    Tickets.push(ticket);
                    this.ComputeTotalPrice();
                }
            }
            else {
                Tickets.push(ticket);
                this.ComputeTotalPrice();
            }
        },
        DeleteByTicketId: function (Id) {
            var ticketsBackup = [];
            for (i = 0; i < Tickets.length; i++) {
                if (Tickets[i].ticketId != Id) {
                    console.log("Adding...");
                    ticketsBackup.push(Tickets[i]);
                }
            }
            Tickets = [];
            for (i = 0; i < ticketsBackup.length; i++) {
                Tickets.push(ticketsBackup[i]);
            }
            this.ComputeTotalPrice();
        },
        ComputeTotalPrice: function () {
            var total = 0;
            for (i = 0; i < Tickets.length; i++) {
                total += Tickets[i].ticketPrice;
            }
            $(TotalAmountSelector).html("Total Price: &#8364;" + AddCommas(total));
            this.UpdateCart();
        },
        UpdateCart: function () {
            $(CartContainer).html("");
            for (i = 0; i < Tickets.length; i++) {

                var choosenNum = "";
                var bonusNum = "";

                for (x = 0; x < Tickets[i].choosenNumbers.length; x++) {
                    choosenNum += Tickets[i].choosenNumbers[x].toString() + ",";
                }
                for (x = 0; x < Tickets[i].choosenBonusNumbers.length; x++) {
                    bonusNum += Tickets[i].choosenBonusNumbers[x].toString() + ",";
                }

                bonusNum = bonusNum.substring(0, bonusNum.length - 1);

                var numbSelected = Tickets[i].choosenNumbers
                $(CartContainer).append("<div class='boxContainer'>" +
                    "<div class='colG'>Box " + (i + 1).toString() + ":</div>" +
                    "<div class='colF'>" + choosenNum + bonusNum
                    + "</div>" +
                    "</div>");
            }
        }
    }
} ();
