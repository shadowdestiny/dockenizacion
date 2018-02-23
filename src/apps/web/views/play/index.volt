{% extends "main.volt" %}
{% block template_css %}
	<link rel="stylesheet" href="/w/css/play.css">
	<link Rel="Canonical" href="{{ language.translate('canonical_euromillions_play') }}" />
{% endblock %}
{% block template_scripts %}
    <script src="/w/js/mobileFix.min.js"></script>
{% endblock %}
{% block template_scripts_code %}
	var ajaxFunctions = {
		playCart : function (params){
			$.ajax({
				url:'/ajax/play-temporarily/temporarilyCart/',
				data:params,
				type:'POST',
				dataType:"json",
				success:function(json){
					if(json.result = 'OK'){
						location.href = json.url;
					}
				},
				error:function (xhr, status, errorThrown){
					{# //EMTD manage errors #}
				},
			});
		}
	};
	{% set dates_draw = play_dates|json_encode %}
	var draw_dates = <?php echo $dates_draw ?>;
	var next_draw_format = '<?php echo $next_draw_format ?>';
	var price_bet = {{ single_bet_price }};
	var next_draw = <?php echo $next_draw; ?>;
	var openTicket = <?php echo $openTicket; ?>;
	var currency_symbol = '<?php echo $currency_symbol ?>';
	var automatic_random = '<?php echo $automatic_random; ?>';
	var discount_lines_title = '{{ language.translate('tittle_multiple') }}';
	var addLinesBtn = '{{ language.translate('addLines_btn') }}';
	var randomizeAllLines = '{{ language.translate('randomizeAll_btn') }}';
	var clearAllLines = '{{ language.translate('clearAll_btn') }}';
	var buyForDraw = "{{ language.translate('buyForDraw') }}";
	var txtLine = '{{ language.translate('line_x') }}';
	var txtMultTotalPrice = '{{ language.translate('mult_total1') }}';
	var txtMultLines = '{{ language.translate('mult_total2') }}';
	var txtMultDraws = '{{ language.translate('mult_total3') }}';
	var txtNextButton = '{{ language.translate('next_btn') }}';
    var tuesday = '{{ language.translate('tuesday') }}';
    var friday = '{{ language.translate('friday') }}';
    var next_draw_date_format = '{{ next_draw_date_format }}';
    var clear_btn = '{{ language.translate('clear_btn') }}';


    {#añadir aqui el translate#}
    var discount_lines = '<?php echo $discount_lines; ?>';
    var draws_number = '<?php echo $draws_number; ?>';
    var discount = '<?php echo $discount; ?>';
    {# end of block with deprecated vars #}

    var __initialState = {
        nextDrawFormat  : '<?php echo $next_draw_format ?>',
        priceBet        : {{ single_bet_price }},
        currencySymbol  : '<?php echo $currency_symbol ?>',
        discountLines   : <?php echo $discount_lines; ?>,
        translations    : {
            discountLinesTitle    : '{{ language.translate('tittle_multiple') }}',
            addLinesBtn           : '{{ language.translate('addLines_btn') }}',
            randomizeAllLines     : '{{ language.translate('randomizeAll_btn') }}',
            clearAllLines         : '{{ language.translate('clearAll_btn') }}',
            buyForDraw            : "{{ language.translate('buyForDraw') }}",
            txtLine               : '{{ language.translate('line_x') }}',
            txtMultTotalPrice     : '{{ language.translate('mult_total1') }}',
            txtMultLines          : '{{ language.translate('mult_total2') }}',
            txtMultDraws          : '{{ language.translate('mult_total3') }}',
            txtNextButton         : '{{ language.translate('next_btn') }}',
            addRandomLineBtn      : 'Add random line',
            pickYourNumbersBtn    : 'Pick your numbers',
            drawsSectionTitle     : '{{ language.translate('tittle_multiple') }}',
            drawsSectionSubtitle  : '{{ language.translate('multiple_discount') }}',
            mobTicketRandomizeBtn : 'Randomize',
            mobTicketClearBtn     : '{{ language.translate('clear_btn') }}',
            mobTicketSubmitBtn    : 'Done',

        }
    };

    if(openTicket){
    showModalTicketClose();
    }

    function showModalTicketClose(){
    $("#closeticket").easyModal({
    top:100,
    autoOpen:true,
    overlayOpacity:0.7,
    overlayColor:"#000",
    transitionIn:'animated fadeIn',
    transitionOut:'animated fadeOut',
    overlayClose: false,
    closeOnEscape: false
    });
    }

    function showModalTicketCloseByLimitBet(){
    $("#closeticketbylimitbet").easyModal({
    top:100,
    autoOpen:true,
    overlayOpacity:0.7,
    overlayColor:"#000",
    transitionIn:'animated fadeIn',
    transitionOut:'animated fadeOut',
    overlayClose: false,
    closeOnEscape: false
    });
    }

{% endblock %}
{% block template_scripts_after %}
    <script src="/w/js/react/play.js"></script>
{% endblock %}

{% block bodyClass %}play{% endblock %}

{% block header %}
    {% set activeNav='{"myClass": "play"}'|json_decode %}
    {% include "_elements/header.volt" %}
{% endblock %}
{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}

{% block body %}
    <main id="content">

        <div class="play--page">

            <div class="banner">
                <div class="top-banner--section">
                <div class="top-banner--banner">
                    <div class="wrapper">

                        <h1 class="top-banner-play">
                            {% if mobile == 1 %}
                                {{ language.translate("play_mobile_h1") }}
                            {% else %}
                                {{ language.translate("play_h1") }}
                            {% endif %}
                        </h1>

                        </div>
                </div>
                </div>
            </div>

            <div class="wrapper">
                <header>

                    {#TODO : Add real variables here#}

                    <div class="left">
                        <div class="resizeme">
                        <div class="bottom">
                            {{ language.translate('tittle') }}
                        </div>
                        </div>
                        <div class="resizeme">
                        <div class="top">
                            {% if next_draw == 5  %}{{ language.translate('friday') }}{% else %}{{ language.translate('tuesday') }}{% endif %}
                        </div>
                        </div>
                    </div>


                    <div class="help-block">
                        <div class="top">
                            {{ language.translate('aboutLottery') }}
                        </div>
                        <div class="bottom">
                            <a href="/{{ language.translate('link_euromillions_help') }}" class="a-hiw">
                                {{ language.translate('play_howbtn') }}
                            </a>
                            <a href="/{{ language.translate('link_euromillions_results') }}" class="a-results">
                                {{ language.translate('play_resultsbtn') }}
                            </a>
                            <a href="/{{ language.translate('link_euromillions_faq') }}" class="a-faq">
                                {{ language.translate('play_faqbtn') }}
                            </a>
                        </div>
                    </div>


                    <div class="right">

                        <div class="top{% if jackpot_value|length > 4  %}-small{% endif %} resizeme">
                            <div>
                            {{ jackpot_value }} Million
                            </div>
                        </div>

                            <div class="resizeme">
                                <div class="bottom">
                            {#{{ language.translate('shortInstruction') }}#}
                                Lorem ipsum dolor sit amet, consectetur
                            </div>
                        </div>
                    </div>



                    {#<h1 class="h3 draw">{{ language.translate("shortInstruction") }}</h1>#}
                        {#<span class="h1 jackpot">#}
                            {#Jackpot#}
                            {#{% set extraClass='{"boxvalueClass": "","currencyClass":"yellow","valueClass":"yellow"}'|json_decode %}#}
                            {#{% include "_elements/jackpot-value" with ['extraClass': extraClass] %}#}
                        {#</span>#}

                </header>

                {# TODO : html version start #}
{#                {% include "_elements/gameplay--section.volt" %}#}
                {# TODO : html version end #}


                <div class="gameplay" id="gameplay"></div>
                <div class="media"></div>



                {% include "_elements/play-bottom-block.volt" %}


            </div>





            <div id="closeticket" class="modal" style="width: 1000px;height: 500px;">
                <div style="text-align: center;color:white">
                    It is too late to buy EuroMillions tickets for the draw held in Paris tonight at 20:45 CET.
                    In a few moments you will be able to purchase EuroMillions tickets for the next draw that will take
                    place on Tuesday.

                    <br><br>Thank you for your pacience.<br>

                    The EuroMillions.com Support Team
                </div>
            </div>
            <div id="closeticketbylimitbet" class="modal" style="width: 1000px;height: 500px;">
                <div style="text-align: center;color:white">
                    It is too late to buy EuroMillions tickets for the draw held in Paris tonight at 20:45 CET.
                    You can be able to purchase EuroMillions tickets for the next draw accessing again to <a href="/">Euromillions.com</a>
                    .

                    <br><br>Thank you for your pacience.<br>

                    The EuroMillions.com Support Team
                </div>
            </div>


        </div>

    </main>
{#   temporary styling for mobile app     #}
    <style>


    </style>
{% endblock %}