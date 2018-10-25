
    {% if mobile != 1 %}
            <div class="add-funds-block second" style="margin-top: -65px; margin-left: 30px">
                <h2 class="h3 yellow margin" style="font-family: Roboto,sans-serif;margin-bottom: 10px;">{{ language.translate("withdraw_head")|upper }}</h2>
                <div class="div-balance"><strong class="purple" style="color:#333;">{{ language.translate("withdraw_balance") }}</strong> <span
                            class="value" style="color:#f0c11c;">{{ wallet.wallet_balance_amount }} {% if symbol != '€' %} ({{ wallet.balance }}) {% endif %}</span></div>
                <div class="div-balance"><strong class="purple" style="color:#333;">{{ language.translate("withdraw_withdrawable") }}:</strong> <span
                            class="value" style="color:#f0c11c;">{{ wallet.wallet_winning_amount }} {% if symbol != '€' %} ({{ wallet.winnings }}) {% endif %}</span></div>
                 <div style="margin-left:-35px!important;">
                <span class="currency" style="color:#f0c11c;font-size: 30px;">{{ symbol }}</span>{{ credit_card_form.render('funds-value', {'style':'left:15px;width: 255px !important;','class':'insert input funds-value-withdraw'~form_errors['funds-value']}) }}
                </div>
                <div class="notes cl">
                <div style="margin-left:-35px!important;">
                    <svg class="ico v-info" style="font-size: 25px;">
                        <use xlink:href="/w/svg/icon.svg#v-info"></use>
                    </svg>
                    <span class="txt"
                          data-txt='{{ language.translate("withdraw_minimum") }} {{ symbol }} {{ site_config.feeLimit }}'>&nbsp;{{ language.translate("Minimum Withdraw is ") }} {{ site_config.feeLimit }}</span>
                 </div>
                 <div style="margin-left:-25px!important;">
                    <span class="txt">{{ language.translate("withdraw_ccyWarning") }}</span>
                 </div>
                </div>
                <br>
                <div class="box-wallet overview">

                    <label class="label submit_withdraw btn gray" style="cursor:pointer">
                        {{ language.translate("withdraw_request_btn") }}
                    </label>
                </div>
            </div>
     {% else %}
    <div class="add-funds-block second" style="margin-top: -65px;">
        <h2 class="h3 yellow margin" style="font-family: Roboto,sans-serif;font-size: 20px;margin-bottom: 15px;">{{ language.translate("withdraw_head")|upper}}</h2>
        <div class="div-balance" style="text-align: left; margin-left:20px;"><strong class="purple" style="color:#333;">{{ language.translate("withdraw_balance") }}</strong> <span
                    class="value" style="color:#f0c11c;">{{ wallet.wallet_balance_amount }} {% if symbol != '€' %} ({{ wallet.balance }}) {% endif %}</span>
        </div>
        <div class="div-balance" style="text-align: left; margin-left:20px;"><strong class="purple" style="color:#333;">{{ language.translate("withdraw_withdrawable") }}:</strong> <span
                            class="value" style="color:#f0c11c;">{{ wallet.wallet_winning_amount }} {% if symbol != '€' %} ({{ wallet.winnings }}) {% endif %}</span>
                </div>
        <div class="div-balance" style="text-align: left;">
        <span class="currency" style="color:#f0c11c;font-size: 25px;right: 0px;">{{ symbol }}</span>{{ credit_card_form.render('funds-value', {'style':'left:5px;width: 200px;','class':'insert input funds-value-withdraw'~form_errors['funds-value']}) }}
        </div>
        <div class="notes cl" style="text-align: left;">
            <svg class="ico v-info" style="font-size: 18px;">
                <use xlink:href="/w/svg/icon.svg#v-info"></use>
            </svg>
            <span class="txt"
                  data-txt='{{ language.translate("withdraw_minimum") }} {{ symbol }} {{ site_config.feeLimit }}'>{{ language.translate("Minimum Withdraw is ") }} {{ site_config.feeLimit }}</span>
            <span class="txt">{{ language.translate("withdraw_ccyWarning") }}</span>
        </div>
        <br>
        <div class="box-wallet-withdraw overview">

                    <label class="label submit_withdraw btn gray" style="cursor:pointer">
                        {{ language.translate("withdraw_request_btn") }}
                    </label>
                </div>
    </div>
    {% endif %}
	<div id="loading-withdraw" class="add-funds-block" style="display:none;">
        	<strong>Cargando...</strong>
    </div>
    <div id="money-matrix-withdraw" class="add-funds-block" style="display:none;">
    	{% include "cart/moneymatrix_iframe.volt" %}
    </div>
