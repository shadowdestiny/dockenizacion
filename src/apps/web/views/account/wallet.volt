{% extends "main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/w/css/account.css">
{% endblock %}
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

$(function(){
    btnShowHide('.new-card', '.box-add-card, .back', '.box-details');
    btnShowHide('.new-bank', '.box-add-bank, .back', '.box-details');
    btnShowHide('.back', '.box-details', '.back, .box-add-bank, .box-add-card');
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
            
            <div class="hidden right back">
                <a class="btn" href="javascript:void(0);">Go Back</a>
            </div>
            <h1 class="h1 title yellow">{{ language.translate("My Wallet") }}</h1>
            <div class="box-details {% if which_form == 'edit' %} hidden {% endif %}">
                <h2 class="h3 yellow">{{ language.translate("Add funds to your wallet") }}</h2>
{# EMTD - Until we are unable to keep the information of the credit card, this code need to be commented
                <table id="card-list" class="table ui-responsive" >
                    <thead>
                        <tr>
                            <th class="cards">{{ language.translate("Your credit card") }}</th>
                            <th class="expire">{{ language.translate("Expires") }}</th>
                            <th class="name">{{ language.translate("Name on the card") }}</th>
                            <th class="action">{{ language.translate("Action") }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {% if payment_methods is empty %}
                        {% else %}
                            {% for payment_method in payment_methods %}
                                <tr class="active">
                                    <td class="cards">
                                        <input name="bankRadio" checked="checked" type="radio" class="radio" data-role="none">
                                        <span class="sprite card mastercard"><span class="txt">Mastercard</span></span>
                                        {{ language.translate('<span class="type">Mastercard</span> that ends with ') }}{{ payment_method.last_number }}
                                    </td>
                                    <td class="expire">{{ payment_method.expiry_date }}</td>
                                    <td class="name">{{ payment_method.cardHolderName}}</td>
                                    <td class="action"><a href="/account/editPayment/{{ payment_method.id_payment }}">{{ language.translate("Edit") }}</a></td>
                                    <td class="action"><a href="/account/remove/{{ payment_method.id_payment }}">{{ language.translate("Delete") }}</a></td>
                                </tr>
                            {% endfor %}
                        {% endif %}

                    </tbody>
                </table>

                <div class="cl margin">
                    <a href="javascript:void(0);" class="new-card btn gwy">{{ language.translate("Add a new Credit card") }}</a>
                </div>
#}

{#    ORIGINAL PIECE OF CODE IN SIMPLE HTML
                         <tr>
                            <td class="cards">
                                <input name="bankRadio" type="radio" class="radio" data-role="none"> 
                                <span class="sprite card mastercard"><span class="txt">Mastercard</span></span>
                                {{ language.translate('<span class="type">Mastercard</span> that ends with 1234') }}
                            </td>
                            <td class="expire">02 2019</td>
                            <td class="name">Mario Rossi</td>
                            <td class="action"><a href="javascript:void(0);">{{ language.translate("Delete") }}</a></td>
                        </tr> #}

                <div class="info box">
                    <svg class="ico v-info"><use xlink:href="/w/icon.svg#info"></use></svg>
                    <span class="txt">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.</span>
                    <a href="javascript:void(0)" class="new-card btn gwy">{{ language.translate("Add a new Credit Card") }}</a>
                </div>

                <div class="cl box-wallet">
                    <div class="value">
                        <span class="purple">{{ language.translate("Wallet balance:") }}</span> &euro; 20 
                    </div>

                    <form class="right">
                        <span class="symbol">&euro;</span>
                        <input class="input" type="text" placeholder="Enter any amount">
                        <label class="label submit btn green">
                            {{ language.translate("Add funds to your wallet") }}
                            <input type="submit" class="hidden">
                        </label>
                    </form>
                </div>

                <hr class="yellow">

                <div class="info box box-congrats">
                    <svg class="ico v-info"><use xlink:href="/w/icon.svg#info"></use></svg>
                    <span class="txt"><span class="congrats">{{ language.translate("Congratulations!!! You won &euro; 100.000") }}</span> Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.</span>
                </div>

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

                <div class="info box">
                    <svg class="ico v-info"><use xlink:href="/w/icon.svg#info"></use></svg>
                    <span class="txt">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.</span>
                    <a href="javascript:void(0)" class="new-bank btn gwy">{{ language.translate("Add a new Bank Account") }}</a>                
                </div>

                <div class="cl box-wallet">
                    <div class="value">
                        <span class="purple">{{ language.translate("Wallet balance:") }}</span> &euro; 500 
                    </div>

                    <form class="right">
                        <span class="symbol">&euro;</span>
                        <input class="input" type="text" placeholder="Enter any amount">
                        <label class="label submit btn blue" for="withdraw">
                            {{ language.translate("Withdraw winnings") }}
                            <input id="withdraw" type="submit" class="hidden">
                        </label>
                    </form>
                </div>
            </div>

    <form class="{% if which_form != 'edit' and which_form%}hidden{% endif %} box-add-card" method="post" action="{% if which_form == 'edit'%}/account/editPayment/{{ payment_method.id_payment }}{% else %}/{% endif %}">
                {% include "account/_add-card.volt" %}
            </form>

            <form class="hidden box-add-bank">
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
                                <option>France</option>
                                <option>Italy</option>
                                <option>Spain</option>
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
    </div>
</main>
{% endblock %}