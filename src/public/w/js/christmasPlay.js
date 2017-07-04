function recalculateTotal()
{
    $('#totalPriceValue').text((parseInt($('#totalTickets').val())*parseFloat($('#singleBetPrice').val())).toFixed(2));

    if ($('#totalTickets').val() > 0) {
        $('#nextButton').addClass('active ui-link');
    } else {
        $('#nextButton').removeClass('active ui-link');
    }
}

$(function(){
    $( "#add_1" ).click(function() {
        $('#totalTickets').val(parseInt($('#totalTickets').val())+1);
        recalculateTotal();
    });

    $( "#remove_1" ).click(function() {
        if ($('#totalTickets').val() != 0) {
            $('#totalTickets').val(parseInt($('#totalTickets').val())-1);
            recalculateTotal();
        }
    });

    $( "#nextButton" ).click(function() {
        if ($('#nextButton').hasClass('active')) {
            $('#christmasForm').submit();
        }
    });
});
