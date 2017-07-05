function recalculateTotal()
{
    $('#totalPriceValue').text((parseInt($('#totalTickets').val())*parseFloat($('#singleBetPrice').val())).toFixed(2));
    $('#showTotalTickets').text(parseInt($('#totalTickets').val()));

    if ($('#totalTickets').val() > 0) {
        $('#nextButton').addClass('active ui-link');
    } else {
        $('#nextButton').removeClass('active ui-link');
    }
}

$(function(){
    $( ".addTicket" ).click(function() {
        var id = this.id.split('_')[1];
        if (parseInt($('#maxTickets_' + id).val()) > parseInt($('#numTickets_' + id).val())) {
            $('#totalTickets').val(parseInt($('#totalTickets').val())+1);
            $('#showNumTickets_' + id).text(parseInt($('#numTickets_' + id).val()) + 1);
            $('#numTickets_' + id).val(parseInt($('#numTickets_' + id).val()) + 1);
            recalculateTotal();
        }
    });

    $( ".removeTicket" ).click(function() {
        var id = this.id.split('_')[1];
        if (parseInt($('#numTickets_' + id).val()) != 0) {
            $('#totalTickets').val(parseInt($('#totalTickets').val())-1);
            $('#showNumTickets_' + id).text(parseInt($('#numTickets_' + id).val()) - 1);
            $('#numTickets_' + id).val(parseInt($('#numTickets_' + id).val()) - 1);
            recalculateTotal();
        }
    });

    $( "#nextButton" ).click(function() {
        if ($('#nextButton').hasClass('active')) {
            $('#christmasForm').submit();
        }
    });
});
