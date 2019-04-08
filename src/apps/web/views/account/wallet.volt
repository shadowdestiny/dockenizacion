{% extends "main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/w/css/account.css">{% endblock %}
{% block bodyClass %}wallet{% endblock %}
{% block template_scripts %}
    <script src="/w/js/mobileFix.js"></script>
    <script type="text/javascript" src="/w/js/csid.js" charset="UTF-8"></script>
    <script>
(function(window) {
  if (window.location !== window.top.location) {
    window.top.location = window.location;
  }
})(this);
</script>
{% endblock %}

{% block template_scripts_code %}
    var txt_depositBuy_btn = "ADD";
    var txt_lottery_title = 'LOTTERY';
    var txt_number_of_draws = 'NUMBER OF DRAWS';
    var txt_starting_date = 'STARTING DATE';
    var txt_ending_date = 'ENDING DATE';
    var txt_amount = 'AMOUNT';
    var txtMultTotalPrice = '{{ language.translate('mult_total1') }}';
    var payment_object = {};

        var form = $(".form-currency");
        payment_object.path         = form.attr("action");
        payment_object.csid         = form.find("#csid").val();
        payment_object.id_payment   = form.find("#id_payment").val();
        payment_object.session_id   = form.find("[name='thm_session_id']").val();
        payment_object.translations = {
            cardNumber : '{{ language.translate("card_number") }}',
            cardHolder : '{{ language.translate("card_name") }}',
            expiryDateMonth : '{{ language.translate("card_date") }}',
            cardCvv : '{{ language.translate("card_cvv") }}',
            dataMessage : '{{ language.translate('ccv_message') }}',
            totalValue: ' {{ language.translate("Pay {total_value}") }}'
        };

    function deleteLnk(id){
    $(id).click(function(e){
    if($(this).closest('tr').hasClass('active')){
    $(this).parent().parent().siblings("tr").addClass("active");
    $(this).parent().parent().siblings("tr").find('input[type=radio]').prop('checked', true);
    $(this).closest('tr').remove();
    }else{
    $(this).closest('tr').remove();
    }
    });
    }

    function checkRadio(id){
    $(id).click(function(e){
    if($(this).hasClass("active")){
    //do nothing
    }else{
    if($(e.target).closest('a').length){ {# // click a link or link's child #}
    {# // Do nothing #}
    }else{
    $(this).siblings("tr").removeClass("active");
    $(this).addClass("active");
    $(this).find('input[type=radio]').prop('checked', true);
    }
    }
    });
    }

    function show_fee_text(value){
    if (parseFloat(value) >= parseFloat(fee_limit)){
    $('.notes span.txt:first').text('No extra fee applied.');
    }else{
    $('.notes span.txt:first').text($('.notes span.txt:first').data('txt'));
    $('.notes').show();
    }
    }


    $('.btn.add-funds').on('click',function(){
    $('#payment-section-wallet,.box.error,  .box.success').show();
    });
    $('.back').on('click',function(){
    $('.box.error, .box.success').hide();
    });
    $('#form-withdraw').on('submit',function(){
    if($('label.submit').hasClass('gray')) {
    return false;
    }
    });

    $('.box-add-card').on('submit', function(){
    var disabled = $('.box-wallet.overview > label.submit').hasClass('gray');
    var cardNumber = $('#card-number');
    if(disabled) {
    return false;
    }
    $('.box-wallet.overview > label.submit').removeClass('green').addClass('gray');
    $('.box-wallet.overview > label.submit').text('Please wait...');
    cardNumber.val(cardNumber.val().replace(/ /g, ''));
    })

    $('#card-cvv,#card-number').on('keypress',function(e){
    var pattern = /^[0-9\.]+$/;
    if(e.target.id == 'card-cvv') {
    pattern = /^[0-9]+$/;
    }
    var codeFF = e.keyCode;
    var code = e.which
    var chr = String.fromCharCode(code);
    if(codeFF == 8 || codeFF == 37 || codeFF == 38 || codeFF == 39 || codeFF == 40 ) {
    return true;
    }
    if(!pattern.test(chr)){
    e.preventDefault();
    }
    });
    var has_enough_winnings = '{{ wallet.hasEnoughWinnings }}'
    $('#amount').on('keyup',function(e){
    if($(this).val() >= 25 && has_enough_winnings) {
    $('label.submit').removeClass('gray').addClass('green');
    } else {
    $('label.submit').removeClass('green').addClass('gray');
    }
    });

    $('#amount').on('keypress',function(e){
    var pattern = /^[0-9\.]+$/;
    var code = e.which
    var chr = String.fromCharCode(code);
    if(!pattern.test(chr)){
    e.preventDefault();
    }
    });


    $('#funds-value').on('keyup',function(){
        var fee_limit = "<?php echo $site_config->feeLimit;?>";
        var value = $(this).val();
        if(value == '') value = 0.00;
        fee_limit = fee_limit.split(/[^\\0-9.]/).join("");
        if(parseFloat(value).toFixed(2) >= parseFloat(fee_limit)) {
            $('.box-wallet.overview > label.submit').removeClass('gray').addClass('green');
        } else {
            $('.box-wallet.overview > label.submit').removeClass('green').addClass('gray');
        }
        if( '{{ symbol }}' !== '€'){
            $('.charge').show();
            $('.value.charge').text('{{ symbol }}' + parseFloat(value).toFixed(2));
            var convert = parseFloat(value).toFixed(2)/parseFloat(<?php echo $ratio; ?>).toFixed(2);
            $('.value.convert').text('(€' + parseFloat(convert).toFixed(2)+ ')');
        } else {
            $('.value.charge').text('{{ symbol }}' + parseFloat(value).toFixed(2));
        }
        $(document).trigger("refreshValuePayment", [ $('.value.charge').text() ]);
    });

    $('#funds-value').on('blur', function(e){
    var value = e.target.value;
    if(value == "" || typeof value == 'undefined'){
    $(this).val("");
    value = 0;
    }else{
    $(this).val(parseFloat(value).toFixed(2));
    }
    show_fee_text(value);
    });


    $('.box-add-card').on('submit',function(){
    $.cookie('csid', $('#csid').val());
    $.cookie('url_gcp',window.location.protocol+'//'+window.location.host+'/euromillions/gcp/deposit');
    });

    $(function(){
    btnShowHide('.btn.add-funds', '.box-add-card, .back', '.overview-wallet'); {# // Add funds #}
    btnShowHide('.btn.withdraw', '.box-bank, .back', '.overview-wallet'); {# // Withdraw winnings #}
    btnShowHide('.btn.convert', '.box-convert, .back', '.overview-wallet');
    btnShowHide('.back', '.overview-wallet', '.back, .box-bank, .box-add-card, .box-convert'); {# // Back button #}
    checkRadio("#card-list tr, #bank-list tr");
    deleteLnk("#card-list .action a, #bank-list .action a");
    });

    /* //Disabled for now the moneymatrix iframe
    $('.submit').on('click', function(e){

		var amount=$('#funds-value').val();
		amount=parseInt(amount)*100;
		if(amount>=1200)
		{
		    $('#funds-value').attr('readonly', true);
		    $('.box-wallet').addClass('disabled');
			$('#money-matrix').hide();
			$('#loading').show();
			$.post('/ajax/funds/order', 'amount='+amount,function(response){
										        let result = JSON.parse(response);
										        $("#iframemx").contents().empty();
												$('#iframemx').attr('src',result.cashier.cashierUrl);
										 })
										  .done(function(response) {
                                                 $('#funds-value').attr('readonly', false);
                                                 $('.box-wallet').removeClass('disabled');
                                                $('#loading').hide();
												$('#money-matrix').show();
                                           });
        }
    });
    */

    $('.submit_withdraw').on('click', function(e){
    		var amount=$('.funds-value-withdraw').val();
    		amount=parseInt(amount)*100;
    		if(amount>=2500)
    		{
    		    $('.funds-value-withdraw').attr('readonly', true);
    		    $('.box-wallet-withdraw').addClass('disabled');
    			$('#money-matrix-withdraw').hide();
    			$('#loading-withdraw').show();
    			$.post('/ajax/withdraw/order', 'amount='+amount,function(response){
    										        let result = JSON.parse(response);
    										        $("#iframemxwithdraw").contents().empty();
    												$('#iframemxwithdraw').attr('src',result.cashier.cashierUrl);
    										 })
    										  .done(function(response) {
                                                     $('.funds-value-withdraw').attr('readonly', false);
                                                     $('.box-wallet-withdraw').removeClass('disabled');
                                                    $('#loading-withdraw').hide();
    												$('#money-matrix-withdraw').show();
                                               });
            }
        });
{% endblock %}
{% block template_scripts_after %}
    <script src="/w/js/react/tooltip.js"></script>
    <script src="/w/js/react/payment.js"></script>
{% endblock %}

{% block header %}
    {% set activeNav='{"myClass": "account"}'|json_decode %}
    {% include "_elements/header.volt" %}
{% endblock %}
{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}

{% block body %}
    <main id="content" class="account-page">
        <div class="wrapper">
            {% include "account/_breadcrumbs.volt" %}
            <div class="nav">
                {% set activeSubnav='{"myClass": "wallet"}'|json_decode %}



                <div class="dashboard-menu--desktop">
                    {% include "account/_nav.volt" %}
                </div>

                <div class="dashboard-menu--mobile--account">
                    <div class="block--img">
                        <div class="img">

                        </div>
                    </div>
                    <div class="block--name">
                        {{user_name}}
                    </div>
                    <div class="block--balance">
                        {{ user_balance }}
                    </div>
                </div>

                <div class="dashboard-menu--mobile dashboard-menu--balance">
                    {% include "account/_nav_mob.volt" %}
                </div>


                <a class="dashboard-menu--mobile--logout" href="/logout">{{ language.translate('LogOut') }}</a>


                <div class="dashboard-menu--mobile--back dashboard-menu--mobile--back--balance">
                    <a href="#">
                        {{ language.translate("myAccount_balance", ['balance' :   user_balance ]) }}
                    </a>
                </div>


            </div>

            <div class="content dashboard-menu--mobile--content">

                {#<h1 class="h1 title yellow">{{ language.translate("balance_head", ['balance' :   user_balance ]) }}</h1>#}

                {% if wallet.current_winnings %}
                    <div class="box info">
                        <svg class="ico v-info">
                            <use xlink:href="/w/svg/icon.svg#v-info"></use>
                        </svg>
                        <div class="txt">
                            <strong class="straight">{{ language.translate("Congratulations!!! You just won ") }}{{ wallet.current_winnings }}</strong><br>
                            {{ language.translate("We have sent you an email with further instructions to collect your prize.
                    For any questions please contact us at") }} <a href="mailto:support@euromillions.com">support@euromillions.com</a>
                        </div>
                    </div>
                {% endif %}
                <div class="{% if show_box_basic == false %}hidden{% endif %} overview-wallet">
                    {#<div class="info box box-congrats">
                       <svg class="ico v-info"><use xlink:href="/w/svg/icon.svg#v-info"></use></svg>
                       <span class="txt"><span class="congrats">{{ language.app("Congratulations! You have won € 89.30") }}</span>
                           {{ language.app("") }}
                       </span>
                   </div>

                   <div class="info box box-congrats">
                       <svg class="ico v-info"><use xlink:href="/w/svg/icon.svg#v-info"></use></svg>
                       <span class="txtTransaction"><span class="congrats">{{ language.app("Congratulations! You have won &euro; 5,500.70") }}</span>
   {#
                       {{ language.app("To transfer your big winnings into your bank account we required the following informations: 1) your full name, 2) passport or ID card, 3) a current residence address, 4) a telephone number and 5) your bank account details.<br> Please send us everything by email to <a href='mailto:support@euromillions.com?subject=I won the lottery'>support@euromillions.com</a>, we will soon get in contact with you.")}}
   #}
                    {#                  </span>
                                  </div>#}

                    {% if msg %}
                        <div class="box success">
                            <svg class="ico v-checkmark">
                                <use xlink:href="/w/svg/icon.svg#v-checkmark"/>
                            </svg>
                            <span class="txt">{{ msg }}</span>
                        </div>
                    {% endif %}
                    {% if errors %}
                        <div class="box error">
                            <svg class="ico v-warning">
                                <use xlink:href="/w/svg/icon.svg#v-warning"/>
                            </svg>
                            <div class="txt">
                                <ul class="no-li">{% for error in errors %}
                                        <li>{{ error }}</li>{% endfor %}</ul>
                            </div>
                        </div>
                    {% endif %}
                    {% if flash.has('error') %}
                        <div class="box error">
                            <svg class="ico v-warning">
                                <use xlink:href="/w/svg/icon.svg#v-warning"/>
                            </svg>
                            <div class="txt">
                                <ul class="no-li">
                                        <li> {{ flash.output() }} </li>
                                </ul>
                            </div>
                        </div>
                    {% endif %}
                    {% if flash.has('success') %}
                        <div class="box success">
                            <svg class="ico v-checkmark">
                                <use xlink:href="/w/svg/icon.svg#v-checkmark"/>
                            </svg>
                            <span class="txt">{{ flash.output() }}</span>
                        </div>
                    {% endif %}

                    <div class="box-balance">
                        <div class="box-balance--row cl">
                            <div class="txt">{{ language.translate("balance_yourBalance") }} <span
                                        class="value">{{ user_balance }}</span></div>
                            <div class="box-btn">
                                <a href="javascript:void(0)"
                                   class="btn big blue add-funds">

                                    {#TODO : Add real variables here#}
                                    {{ language.translate("balance_deposit_btn") }}
                                    {#Deposit#}
                                </a>
                            </div>
                        </div>
                        <div class="box-balance--row  cl" style="height:360px">
                            <div class="txt">{{ language.translate("balance_yourSubscription") }} </div>
                            <div class="txt">{{ language.translate("EuroMillions") }} <span
                                        class="value">{{ wallet.subscriptionBalanceEuromillions}}</span></div>
                            <div class="txt">{{ language.translate("PowerBall") }} <span
                                        class="value">{{ wallet.subscriptionBalancePowerBall}}</span></div>
                            <div class="txt">{{ language.translate("MegaMillions") }} <span
                                        class="value">{{ wallet.subscriptionBalanceMegaMillions}}</span></div>
                            <div class="txt">
                                 EuroJackpot
                                 <span class="value">{{ wallet.subscriptionBalanceEuroJackpot}}</span>
                            </div>
                            <div class="txt">
                                 MegaSena
                                 <span class="value">{{ wallet.subscriptionBalanceMegaSena}}</span>
                            </div>

                        </div>
                        <div class="box-balance--row  cl">
                            <div class="txt">{{ language.translate("balance_yourWithdrawable") }} <span
                                        class="value">{{ wallet.wallet_winning_amount }}</span></div>
                            {% if wallet.hasEnoughWinnings %}
                            <div class="box-btn">
                                <a href="javascript:void(0)"
                                   class="btn big green withdraw">

                                    {#TODO : Add real variables here#}
                                    {{ language.translate("balance_withdraw_btn") }}
                                    {#Withdraw#}
                                </a>
                            </div>
                            {% endif %}
                        </div>


                    </div>
                </div>
                <div class="balance-section">
                {#<form class="{% if show_form_add_fund == false %}hidden{% endif %} box-add-card form-currency" method="post" action="/addFunds">
                                    {% set component='{"where": "account"}'|json_decode %}
                                    {% include "account/_add-card.volt" %}
                                    <input type="hidden" id="csid" name="csid"/>
                </form>#}
                <form class="{% if show_form_add_fund == false %}hidden{% endif %} box-add-card form-currency">
                    {% set component='{"where": "account"}'|json_decode %}
                    {% include "account/_add-money-matrix.volt" %}
                    <input type="hidden" id="csid" name="csid"/>
                </form>
                <br />
                <div id="payment-section-wallet" style="display:none"></div>
                <div>
                </div>
                <div class="box-bank {% if which_form != 'withdraw' %}hidden{% endif %}">
                    {% if msg %}
                        <div class="box success">
                            <svg class="ico v-checkmark">
                                <use xlink:href="/w/svg/icon.svg#v-checkmark"/>
                            </svg>
                            <span class="txt">{{ msg }}</span>
                        </div>
                    {% endif %}

                    {% if errors %}
                        <div class="box error">
                            <svg class="ico v-warning">
                                <use xlink:href="/w/svg/icon.svg#v-warning"/>
                            </svg>
                            <div class="txt">
                                <ul class="no-li">{% for error in errors %}
                                        <li>{{ error }}</li>{% endfor %}</ul>
                            </div>
                        </div>
                    {% endif %}
                    <h2 class="h3 yellow">{{ language.translate("withdraw_head") }}</h2>

                    <form action="/withdraw" method="post" id="form-withdraw" class="box-add-bank">
                        <div class="box-details {#{% if which_form == 'edit' %} hidden {% endif %}#}">
                            <div class="cl box-wallet">
                                <div class="value">
                                    <span class="purple">{{ language.translate("withdraw_balance") }}</span> {{ wallet.wallet_balance_amount }} {% if symbol != '€' %} ({{ wallet.balance }}) {% endif %}
                                </div>
                                <br>
                                <div class="left value">
                                    <span class="purple">{{ language.translate("withdraw_withdrawable") }}</span> {{ wallet.wallet_winning_amount }} {% if symbol != '€' %} ({{ wallet.winnings }}) {% endif %}
                                </div>
                                <br>
                                <div class="value">
                                    <span class="subtxt grey-lighter">{{ language.translate("withdraw_minimum") }}</span>
                                </div>
                                <br>
                                <div class="value">
                                    <span class="subtxt grey-lighter">{{ language.translate("withdraw_ccyWarning") }}</span>
                                </div>
                                <br>
                                <div class="form-currency cl">
                                    <br>
                                    {#<span class="currency">&euro;</span>#}
                                    {{ bank_account_form.render('amount', {'class':'withdraw_amount input insert'~form_errors['amount']}) }}
                                </div>
                            </div>
                        </div>

                        <div class="">
                            <div class="">
                                <div class="bank-details-block">
                                    <h2 class="h3 yellow">{{ language.translate("withdraw_bank") }}</h2>

                                    <label class="label" for="add-bank-user">
                                        {{ language.translate("withdraw_name") }} <span class="asterisk">*</span> <span
                                                class="subtxt">({{ language.translate("bank account holder name") }}</span>
                                    </label>
                                    {{ bank_account_form.render('name', {'class':'input'~form_errors['name']}) }}

                                    <label class="label" for="add-bank-user-surname">
                                        {{ language.translate("withdraw_surname") }} <span class="asterisk">*</span>
                                        <span class="subtxt">({{ language.translate("bank account holder") }})</span>
                                    </label>
                                    {{ bank_account_form.render('surname', {'class':'input'~form_errors['surname']}) }}
                                    <label class="label" for="add-bank-name">
                                        {{ language.translate("withdraw_bankname") }} <span class="asterisk">*</span>
                                    </label>
                                    {{ bank_account_form.render('bank-name', {'class':'input'~form_errors['bank-name']}) }}
                                    <label class="label" for="add-bank-iban">
                                        {{ language.translate("withdraw_iban") }} <span class="asterisk">*</span>
                                    </label>
                                    {{ bank_account_form.render('bank-account', {'class':'input'~form_errors['bank-account']}) }}
                                    <label class="label" for="add-bank-bic">
                                        {{ language.translate("withdraw_bic") }} <span class="asterisk">*</span>
                                    </label>
                                    {{ bank_account_form.render('bank-swift', {'class':'input'~form_errors['bank-swift']}) }}
                                </div>
                                <div class="">
                                    <label class="label" for="add-bank-country">
                                        {{ language.translate("withdraw_county") }} <span class="asterisk">*</span>
                                    </label>
                                    <div class="selectbox">
                                    {{ bank_account_form.render('country', {'disabled':'disabled','class':'select'~form_errors['country']}) }}
                                    </div>

                                    <label class="label" for="add-bank-address">
                                        {{ language.translate("withdraw_address") }} <span class="asterisk">*</span>
                                    </label>
                                    {{ bank_account_form.render('street', {'class':'input'~form_errors['street']}) }}
                                    <label class="label" for="add-bank-city">
                                        {{ language.translate("withdraw_city") }} <span class="asterisk">*</span>
                                    </label>
                                    {{ bank_account_form.render('city', {'class':'input'~form_errors['city']}) }}
                                    <label class="label" for="add-bank-postal">
                                        {{ language.translate("withdraw_zip") }} <span class="asterisk">*</span>
                                    </label>
                                    {{ bank_account_form.render('zip', {'class':'input'~form_errors['zip']}) }}
                                    <label class="label" for="add-bank-phone">
                                        {{ language.translate("withdraw_phone") }} <span class="asterisk">*</span>
                                    </label>
                                    {{ bank_account_form.render('phone_number', {'class':'input'~form_errors['phone_number']}) }}
                                </div>
                            </div>
                        </div>
                        <div class="cl">
                            <label class="label submit btn gray" style="cursor:default; float: left;" for="new-bank">
                                {{ language.translate("withdraw_request_btn") }}
                                <input id="new-bank" type="submit" class="hidden">
                            </label>
                        </div>
                    </form>
                    {#<form id="form-withdraw" class="box-add-bank">
                            {% include "account/_add-money-matrix-withdraw.volt" %}
                    </form>#}
                </div>
            </div>
        </div>
    </main>
{% endblock %}

