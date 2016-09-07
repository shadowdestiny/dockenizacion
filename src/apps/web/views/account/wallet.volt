{% extends "main.volt" %}
{% block template_css %}<link rel="stylesheet" href="/w/css/account.css">{% endblock %}
{% block bodyClass %}wallet{% endblock %}
{% block template_scripts %}
    <script src="/w/js/mobileFix.js"></script>
    <script type="text/javascript" src="/w/js/csid.js" charset="UTF-8"></script>
{% endblock %}
{% block template_scripts_code %}
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
    $('.box.error,  .box.success').show();
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
        if(disabled) {
            return false;
        }
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
    if(parseFloat(value).toFixed(2) >= parseFloat(fee_limit.substring(1)).toFixed(2)) {
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
{% endblock %}
{% block template_scripts_after %}<script src="/w/js/react/tooltip.js"></script>{% endblock %}
{% block header %}
    {% set activeNav='{"myClass": "account"}'|json_decode %}
    {% include "_elements/header.volt" %}
{% endblock %}
{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}

{% block body %}
    <main id="content">
        <div class="wrapper">
            <div class="nav box-basic">
                {% set activeSubnav='{"myClass": "wallet"}'|json_decode %}
                {% include "account/_nav.volt" %}
            </div>

            <div class="box-basic content">
                <div class="{%if show_box_basic == true %}hidden{% endif %} right back cl">
                    <a class="btn" href="javascript:void(0);">Go Back</a>
                </div>
                <h1 class="h1 title yellow">{{ language.translate("Balance", ['balance' :   user_balance ]) }}</h1>

                {% if wallet.current_winnings %}
                    <div class="box info">
                        <svg class="ico v-info"><use xlink:href="/w/svg/icon.svg#v-info"></use></svg>
                        <div class="txt">
                            <strong class="straight">{{ language.translate("Congratulations!!! You just won ") }}{{ wallet.current_winnings }}</strong><br>
                            {{ language.translate("We have sent you an email with further instructions to collect your prize.
                    For any questions please contact us at") }} <a href="mailto:support@euromillions.com">support@euromillions.com</a>
                        </div>
                    </div>
                {% endif %}
                <div class="{%if show_box_basic == false %}hidden{% endif %} overview-wallet">
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
                            <svg class="ico v-checkmark"><use xlink:href="/w/svg/icon.svg#v-checkmark"/></svg>
                            <span class="txt">{{ msg }}</span>
                        </div>
                    {% endif %}
                    {% if errors %}
                        <div class="box error">
                            <svg class="ico v-warning"><use xlink:href="/w/svg/icon.svg#v-warning"/></svg>
                            <div class="txt"><ul class="no-li">{% for error in errors %}<li>{{ error }}</li>{% endfor %}</ul></div>
                        </div>
                    {% endif %}

                    <div class="box-balance">
                        <div class="border cl">
                            <div class="txt">{{ language.translate("Your balance:") }} <span class="value">{{ user_balance }}</span></div>
                            <div class="box-btn">
                                <a href="javascript:void(0)" class="btn big blue add-funds">{{ language.translate("Add funds of your balance") }}</a>
                            </div>
                        </div>
                        <br>
                        <div class="border cl">
                            <div class="txt">{{ language.translate("Your Withdrawable Balance:")}} <span class="value">{{ wallet.wallet_winning_amount }}</span></div>
                            <div class="box-btn">
                                <a href="javascript:void(0)" class="btn big green withdraw">{{ language.translate("Make Withdrawal") }}</a>
                            </div>
                        </div>
                    </div>
                </div>
                <form class="{%if show_form_add_fund == false %}hidden{% endif %} box-add-card form-currency" method="post" action="/addFunds">
                    {% set component='{"where": "account"}'|json_decode %}
                    {% include "account/_add-card.volt" %}
                    <input type="hidden" id="csid" name="csid"/>
                </form>
                <div class="box-bank {% if which_form != 'withdraw' %}hidden{% endif %}">
                    {% if msg %}
                        <div class="box success">
                            <svg class="ico v-checkmark"><use xlink:href="/w/svg/icon.svg#v-checkmark"/></svg>
                            <span class="txt">{{ msg }}</span>
                        </div>
                    {% endif %}

                    {% if errors %}
                        <div class="box error">
                            <svg class="ico v-warning"><use xlink:href="/w/svg/icon.svg#v-warning"/></svg>
                            <div class="txt"><ul class="no-li">{% for error in errors %}<li>{{ error }}</li>{% endfor %}</ul></div>
                        </div>
                    {% endif %}

                    <h2 class="h3 yellow">{{ language.translate("Withdraw your winnings") }}</h2>

                    <form action="/withdraw" method="post" id="form-withdraw" class="box-add-bank">
                        <div class="box-details {#{% if which_form == 'edit' %} hidden {% endif %}#}">
                            <div class="cl box-wallet">
                                <div class="value">
                                    <span class="purple">{{ language.translate("Account balance:") }}</span> {{ wallet.wallet_balance_amount }} {% if symbol != '€' %} ({{ wallet.balance }}) {% endif %}
                                </div>
                                <br>
                                <div class="left value">
                                    <span class="purple">{{ language.translate("Withdrawable:") }}</span> {{ wallet.wallet_winning_amount }} {% if symbol != '€' %} ({{ wallet.winnings }}) {% endif %}
                                </div>
                                <br>
                                <div class="value">
                                    <span class="subtxt grey-lighter">{{ language.translate("Minimum Withdrawal is €25") }}</span>
                                </div>
                                <br>
                                <div class="value">
                                    <span class="subtxt grey-lighter">{{ language.translate("Currencies are just informative, withdrawals must be made in Euros") }}</span>
                                </div>
                                <br>
                                <div class="right form-currency cl">
                                    <span class="currency">&euro;</span>
                                    {{ bank_account_form.render('amount', {'class':'withdraw_amount input insert'~form_errors['amount']}) }}
                                </div>
                            </div>
                        </div>
                        <h2 class="h3 yellow">{{ language.translate("Bank account details") }}</h2>

                        <div class="wrap">
                            <div class="cols">
                                <div class="col6">
                                    <label class="label" for="add-bank-user">
                                        {{ language.translate("Name") }} <span class="asterisk">*</span> <span class="subtxt">({{ language.translate("bank account holder name") }}</span>
                                    </label>
                                    {{ bank_account_form.render('name', {'class':'input'~form_errors['name']}) }}

                                    <label class="label" for="add-bank-user-surname">
                                        {{ language.translate("Surname") }} <span class="asterisk">*</span> <span class="subtxt">({{ language.translate("bank account holder") }})</span>
                                    </label>
                                    {{ bank_account_form.render('surname', {'class':'input'~form_errors['surname']}) }}
                                    <label class="label" for="add-bank-name">
                                        {{ language.translate("Bank Name") }} <span class="asterisk">*</span>
                                    </label>
                                    {{ bank_account_form.render('bank-name', {'class':'input'~form_errors['bank-name']}) }}
                                    <label class="label" for="add-bank-iban">
                                        {{ language.translate("IBAN or Bank account") }} <span class="asterisk">*</span>
                                    </label>
                                    {{ bank_account_form.render('bank-account', {'class':'input'~form_errors['bank-account']}) }}
                                    <label class="label" for="add-bank-bic">
                                        {{ language.translate("BIC or SWIFT") }} <span class="asterisk">*</span>
                                    </label>
                                    {{ bank_account_form.render('bank-swift', {'class':'input'~form_errors['bank-swift']}) }}
                                </div>
                                <div class="col6">
                                    <label class="label" for="add-bank-country">
                                        {{ language.translate("Country of residence") }} <span class="asterisk">*</span>
                                    </label>
                                    {{ bank_account_form.render('country', {'disabled':'disabled','class':'select'~form_errors['country']}) }}

                                    <label class="label" for="add-bank-address">
                                        {{ language.translate("Address") }} <span class="asterisk">*</span>
                                    </label>
                                    {{ bank_account_form.render('street', {'class':'input'~form_errors['street']}) }}
                                    <label class="label" for="add-bank-city">
                                        {{ language.translate("City") }} <span class="asterisk">*</span>
                                    </label>
                                    {{ bank_account_form.render('city', {'class':'input'~form_errors['city']}) }}
                                    <label class="label" for="add-bank-postal">
                                        {{ language.translate("Postal Code") }} <span class="asterisk">*</span>
                                    </label>
                                    {{ bank_account_form.render('zip', {'class':'input'~form_errors['zip']}) }}
                                    <label class="label" for="add-bank-phone">
                                        {{ language.translate("Phone number") }} <span class="asterisk">*</span>
                                    </label>
                                    {{ bank_account_form.render('phone_number', {'class':'input'~form_errors['phone_number']}) }}
                                </div>
                            </div>
                        </div>
                        <div class="cl">
                            <label class="label submit btn gray" style="cursor:default" for="new-bank">
                                {{ language.translate("Request Withdrawal") }}
                                <input id="new-bank" type="submit" class="hidden">
                            </label>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
{% endblock %}

