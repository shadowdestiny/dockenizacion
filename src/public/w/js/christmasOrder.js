$(function(){
    $('.buy').on('click',function(){
        if ($(this).text() == 'Buy now') {
            $(this).text('Please wait...');
            $(this).css('pointer-events', 'none');
        }

        var value = $(this).data('btn');

        if(value == 'no-wallet') {
            if ($('#pay-wallet').is(':checked')) {
                $('.submit.big.green').text('Pay ' + currency_symbol + ' ' + priceWithWallet.toFixed(2));
            } else {
                $('.submit.big.green').text('Pay ' + currency_symbol + ' ' + total_price);
            }
            $('#pay-wallet').attr('readonly',true);
            $('.payment').show();
            $('.box-bottom').hide();
            var $root = $('html, body');
            $root.animate({
                scrollTop: $('#card-number').offset().top
            }, 500);
            $('#card-number').focus();
        } else {
            $('.payment').hide();
        }
    });
});

$('#pay-wallet').on('click', function(){
    if (this.checked) {
        if (payTotalWallet) {
            $('.buy').attr('href', '/christmas/payment?method=wallet');
            $('.buy').attr('data-btn', 'wallet');
            $('.buy').text('Buy now');
            $('.balance-price').text('- ' + currency_symbol + ' ' + total_price);
            $('.box-bottom').show();
            $('.payment').hide();
            $('.cont').hide();
            $('.money').hide();
        } else {
            $('.buy').attr('href', 'javascript:void(0);');
            $('.buy').attr('data-btn', 'no-wallet');
            $('.buy').text('Continue');
            $('.val').text(currency_symbol + ' ' + priceWithWallet.toFixed(2));
            $('.balance-price').text('- ' + currency_symbol + ' ' + wallet_balance);
            $('.submit.big.green').text('Pay ' + currency_symbol + ' ' + priceWithWallet.toFixed(2));
        }
    } else {
        $('.buy').attr('href', 'javascript:void(0);');
        $('.buy').attr('data-btn', 'no-wallet');
        $('.cont').show();
        $('.money').show();
        $('.val').text(currency_symbol + ' ' + total_price);
        $('.balance-price').text(currency_symbol + ' 0.00');
        $('.submit.big.green').text('Pay ' + currency_symbol + ' ' + total_price);
    }
});

$('.box-add-card').on('submit',function(){
    var disabled = $('label.submit').hasClass('gray');
    var cardNumber = $('#card-number');
    var error = 0;
    if(disabled) {
        return false;
    }
    cardNumber.val(cardNumber.val().replace(/ /g, ''));

    if (cardNumber.val().length != 16 || isNaN(cardNumber.val())) {
        error = 1;
    }

    if ($('#card-holder').val().replace(/ /g, '') == '' || $('#expiry-date-month').val().replace(/ /g, '') == '' ||
        $('#expiry-date-year').val().replace(/ /g, '') == '' || $('#card-cvv').val().replace(/ /g, '') == '') {
        error = 1;
    }

    if (error == 1) {
        alert ('You must fill in the card details');
        return false;
    }

    $('label.submit').removeClass('green').addClass('gray');
    $('label.submit').text('Please wait...');

    $('#paywallet').val($('#pay-wallet').is(':checked') ? true : false);
    $('#funds').val($('#charge').val());
    $.cookie('csid', $('#csid').val());
    $.cookie('url_gcp', window.location.protocol+'//'+window.location.host+'/euromillions/gcp');
});

$('#expiry-date-month').on('keyup',function(e){
    var charCount = $(this).val().length;
    if (charCount==2){
        $('#expiry-date-year').focus();
    }
});

$('#expiry-date-year').on('keyup',function(e){
    var charCount = $(this).val().length;
    if (charCount==2){
        $('#card-cvv').focus();
    }
});

$('#card-cvv,#card-number').on('keypress',function(e){
    var pattern = /^[0-9\.]+$/;
    if(e.target.id == 'card-cvv') {
        pattern = /^[0-9]+$/;
    }
    var codeFF = e.keyCode;
    var code = e.which;
    var chr = String.fromCharCode(code);
    if(codeFF == 8 || codeFF == 37 || codeFF == 38 || codeFF == 39 || codeFF == 40 ) {
        return true;
    }
    if(!pattern.test(chr)){
        e.preventDefault();
    }
});