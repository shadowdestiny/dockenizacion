{% extends "main.volt" %}
{% block template_css %}<link rel="stylesheet" href="/w/css/account.css">{% endblock %}
{% block bodyClass %}wallet{% endblock %}

{% block template_scripts %}
<script>
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
            if($(e.target).closest('a').length){ // click a link or link's child
                // Do nothing
            }else{
                $(this).siblings("tr").removeClass("active");
                $(this).addClass("active");
                $(this).find('input[type=radio]').prop('checked', true);
            }
        }
    });
}


$('#funds-value').on('keyup',function(e) {
    ///^\D+(\.\D\D?)?$/
    var regex = /^\d+(\.\d{0,2})?$/g;
});

$('#funds-value').on('keypress',function(e) {
    var pattern = /^[0-9\.]+$/;
    var chr = String.fromCharCode(event.which);
    if(!pattern.test(chr)){
        event.preventDefault();
    }
});

$('#funds-value').on('blur', function(e) {
    var value = e.target.value;
    if(value == "" || typeof value == 'undefined' ) {
        $(this).val("");
    } else {
        $(this).val(parseFloat(value).toFixed(2));
    }
});

$(function(){
    btnShowHide('.btn.add-funds', '.box-add-card, .back', '.overview-wallet'); // Add funds
    btnShowHide('.btn.withdraw', '.box-bank, .back', '.overview-wallet'); // Withdraw winnings
    btnShowHide('.btn.convert', '.box-convert, .back', '.overview-wallet');
    btnShowHide('.back', '.overview-wallet', '.back, .box-bank, .box-add-card, .box-convert'); // Back button
    checkRadio("#card-list tr, #bank-list tr");
    deleteLnk("#card-list .action a, #bank-list .action a");
});
</script>
{% endblock %} 

{% block header %}
    {% set activeNav='{"myClass": "account"}'|json_decode %}
    {% include "_elements/header.volt" %}
{% endblock %}
{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}

{% block body %}
{#

    * NO DATA & DATA: VARIOUS COMBINATIONS IN THIS CODE, CHECK THE GRAPHICS (no bank account set up, no credit card set up, winning infobox) *
#}

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
            <h1 class="h1 title yellow">{{ language.translate("Wallet") }}</h1>

            <div class="{%if show_box_basic == false %}hidden{% endif %} overview-wallet">
                {#<div class="info box box-congrats">
                    <svg class="ico v-info"><use xlink:href="/w/svg/icon.svg#v-info"></use></svg>
                    <span class="txt"><span class="congrats">{{ language.translate("Congratulations! You have won € 89.30") }}</span>
                        {{ language.translate("") }}
                    </span>
                </div>

                <div class="info box box-congrats">
                    <svg class="ico v-info"><use xlink:href="/w/svg/icon.svg#v-info"></use></svg>
                    <span class="txt"><span class="congrats">{{ language.translate("Congratulations! You have won &euro; 5,500.70") }}</span> 
{#
                    {{ language.translate("To transfer your big winnings into your bank account we required the following informations: 1) your full name, 2) passport or ID card, 3) a current residence address, 4) a telephone number and 5) your bank account details.<br> Please send us everything by email to <a href='mailto:support@euromillions.com?subject=I won the lottery'>support@euromillions.com</a>, we will soon get in contact with you.")}}
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
                    <div class="cols res">
                        <div class="col5">
                            <div class="border">
                                <div class="txt">{{ language.translate("Your current balance:") }} <span class="value">{{ user_balance }}</span></div>
                                <a href="javascript:void(0)" class="btn blue add-funds">{{ language.translate("Add funds of your wallet") }}</a>
                            </div>
                        </div>
                        <div class="col7">
                            <div class="border">
                                <div class="txt">{{ language.translate("Your winnings:")}} <span class="value">&euro; 20.00</span></div>
                                <a href="javascript:void(0)" class="btn blue convert">{{ language.translate("Convert winnings into your wallet")}}</a>
                                <a href="javascript:void(0)" class="btn green withdraw">{{ language.translate("Withdraw winnings") }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <form class="{%if show_form_add_fund == false %}hidden{% endif %} box-add-card form-currency {#{% if which_form != 'edit' and which_form%}hidden{% endif %}#}" method="post" action="/account/addFunds{#{% if which_form == 'edit'%}/account/editPayment/{{ payment_method.id_payment }}{% else %}/{% endif %}#}">
                {% set component='{"where": "account"}'|json_decode %}
                {% include "account/_add-card.volt" %}
            </form>

            <div class="box-bank hidden">
                *With data*

                <h2 class="h3 yellow">{{ language.translate("Withdraw winnings") }}</h2>
                <div class="box-details {#{% if which_form == 'edit' %} hidden {% endif %}#}">
                    <table id="bank-list" class="table ui-responsive">
                        <thead>
                            <tr>
                                <th class="bank">{{ language.translate("Bank name") }}</th>
                                <th class="id">{{ language.translate("Identification") }}</th>
                                <th class="expire">{{ language.translate("Account Owner") }}</th>
                                <th class="action">{{ language.translate("Action") }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="active">
                                <td class="bank">
                                    <input name="cardRadio" checked="checked" type="radio" class="radio" data-role="none">
                                    Santander
                                </td>
                                <td class="id">GR 0110 1250 0000 0001 2300 695</td>
                                <td class="expire">Mario Rossi</td>
                                <td class="action">
                                    <a href="javascript:void(0);">{{ language.translate("Edit") }}</a>
                                </td>
                                <td class="action">
                                    <a href="javascript:void(0);">{{ language.translate("Delete") }}</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="bank">
                                    <input name="cardRadio" type="radio" class="radio" data-role="none">
                                    Santander
                                </td>
                                <td class="id">GR 0110 1250 0000 0001 2300 695</td>
                                <td class="expire">Mario Rossi</td>
                                <td class="action">
                                    <a href="javascript:void(0);">{{ language.translate("Delete") }}</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="cl margin">
                        <a href="javascript:void(0);" class="new-bank btn gwy">{{ language.translate("Add a new Bank account") }}</a>
                    </div>

                *NO data*
                    <div class="info box special">
                        <svg class="ico v-info"><use xlink:href="/w/svg/icon.svg#v-info"></use></svg>
                        <span class="txt">{{ language.translate("Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.") }}</span>
                        <a href="javascript:void(0)" class="new-bank btn gwy">{{ language.translate("Add a new Bank Account") }}</a>                
                    </div>

                *Show always with or without DATA*
                    <div class="cl box-wallet">
                        <div class="value">
                            <span class="purple">{{ language.translate("Wallet balance:") }}</span> &euro; 500.00 
                        </div>

                        <form class="right form-currency">
                            <span class="currency">&euro;</span>
                            <input class="input insert" type="text" placeholder="{{ language.translate('Insert an amount') }}">
                            <label class="label submit btn blue" for="withdraw">
                                {{ language.translate("Withdraw winnings") }}
                                <input id="withdraw" type="submit" class="hidden">
                            </label>
                        </form>
                    </div>
                </div>

                <form class="box-add-bank">
                    <h2 class="h3 yellow">{{ language.translate("Bank account details") }}</h2>

                    <div class="wrap">
                        <div class="cols">
                            <div class="col6">
                                <label class="label" for="add-bank-user">
                                    {{ language.translate("Name") }} <span class="asterisk">*</span> <span class="subtxt">({{ language.translate("bank account holder name") }}</span>
                                </label>
                                <input id="add-bank-user" class="input" type="text">

                                <label class="label" for="add-bank-user-surname">
                                    {{ language.translate("Surname") }} <span class="asterisk">*</span> <span class="subtxt">({{ language.translate("bank account holder") }})</span>
                                </label>
                                <input id="add-bank-surname" class="input" type="text">

                                <label class="label" for="add-bank-name">
                                    {{ language.translate("Bank Name") }} <span class="asterisk">*</span>
                                </label>
                                <input id="add-bank-name" class="input" type="text">

                                <label class="label" for="add-bank-iban">
                                    {{ language.translate("IBAN or Bank account") }} <span class="asterisk">*</span>
                               </label>
                                <input id="add-bank-iban" class="input" type="text">

                                <label class="label" for="add-bank-bic">
                                    {{ language.translate("BIC or SWIFT") }} <span class="asterisk">*</span>
                                </label>
                                <input id="add-bank-bic" class="input" type="text" disabled>
                            </div>
                            <div class="col6">
                               <label class="label" for="add-bank-country">
                                    {{ language.translate("Country of residence") }} <span class="asterisk">*</span> 
                                </label>
                                <select id="add-bank-country" class="select">
                                    <option>{{ language.translate("Select your country") }}</option>
                                    <option>{{ language.translate("France")}}</option>
                                    <option>{{ language.translate("Italy") }}</option>
                                    <option>{{ language.translate("Spain")}}</option>
                                </select>

                                <label class="label" for="add-bank-address">
                                    {{ language.translate("Address") }} <span class="asterisk">*</span>
                                </label>
                                <input id="add-bank-address" class="input" type="text">

                                <label class="label" for="add-bank-city">
                                    {{ language.translate("City") }} <span class="asterisk">*</span>
                                </label>
                                <input id="add-bank-city" class="input" type="text">

                                <label class="label" for="add-bank-postal">
                                    {{ language.translate("Postal Code") }} <span class="asterisk">*</span>
                                </label>
                                <input id="add-bank-postal" class="input" type="text">

                                <label class="label" for="add-bank-phone">
                                    {{ language.translate("Phone number") }} <span class="asterisk">*</span>
                                </label>
                                <input id="add-bank-phone" class="input" type="text">
                            </div>
                        </div>
                    </div>
                    <div class="cl">
                        <label class="label submit btn green" for="new-bank">
                            {{ language.translate("Add your Bank Account") }}
                            <input id="new-bank" type="submit" class="hidden">
                        </label>
                    </div>
                </form>
            </div>

            <div class="box-convert hidden">
                <h2 class="h3 yellow">{{ language.translate("Convert your winnings into your wallet") }}</h2>
                <div class="info box">
                    <svg class="ico v-info"><use xlink:href="/w/svg/icon.svg#v-info"></use></svg>
                    <span class="txt">{{ language.translate("Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.") }}</span>
                </div>

                <div class="cl box-wallet wrap-value">
                    <div class="value">
                        <span class="purple">{{ language.translate("Wallet balance:") }}</span> {{ user_balance }}
                    </div>

                    <div class="value">
                        <span class="purple">{{ language.translate("Winnings:") }}</span> &euro; 500.00 
                    </div>

                    <form class="right form-currency">
                        <span class="currency">&euro;</span>
                        <input class="input insert" type="text" placeholder="{{ language.translate('Insert an amount')}}">
                        <label class="label submit btn blue" for="withdraw">
                            {{ language.translate("Convert winnings") }}
                            <input id="withdraw" type="submit" class="hidden">
                        </label>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
{% endblock %}