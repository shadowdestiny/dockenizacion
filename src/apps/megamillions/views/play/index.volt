{% extends "../../shared/views/play/index.volt" %}

{% block template_scripts_code %}
    var ajaxFunctions = {
    playCart : function (params){
    $.ajax({
    url:'/ajax/power-ball-play-temporarily/temporarilyCart/',
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
    var discount_lines_title = '{{ language.translate('pow_multiple') }}';
    var addLinesBtn = '{{ language.translate('addLines_btn') }}';
    var randomizeAllLines = '{{ language.translate('randomizeAll_btn') }}';
    var clearAllLines = '{{ language.translate('clearAll_btn') }}';
    var buyForDraw = "{{ language.translate('buyForDraw') }}";
    var txtLine = '{{ language.translate('line_x') }}';
    var txtMultTotalPrice = '{{ language.translate('mult_total1') }}';
    var txtMultLines = '{{ language.translate('mult_total2') }}';
    var txtMultDraws = '{{ language.translate('mult_total3') }}';
    var txtNextButton = '{{ language.translate('next_btn') }}';
    var draw_day = '<?php echo $draw_day; ?>';
    var next_draw_date_format = '{{ next_draw_date_format }}';
    var clear_btn = '{{ language.translate('clear_btn') }}';
    var addlines_message = "{{ language.translate('addlines_message') }}";
    var powerplay = '0';



    {#añadir aqui el translate#}
    var discount_lines = '<?php echo $discount_lines; ?>';
    var draws_number = '<?php echo $draws_number; ?>';
    var discount = '<?php echo $discount; ?>';
    {# end of block with deprecated vars #}

    var __initialState = {
        mode            : 'megamillions',
        nextDrawFormat  : '<?php echo $draw_day . ' ' .$next_draw_date_format ?>',
        priceBet        : {{ single_bet_price }},
        currencySymbol  : '<?php echo $currency_symbol ?>',
        discountLines   : <?php echo $discount_lines; ?>,
        drawDateFormat  : '{{ next_draw_date_format }}',
        powerPlayPrice  : 1.5,
        playDate        : '<?php echo explode('#', json_decode($dates_draw)[0][0])[0] ?>',
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
            addRandomLineBtn      : '{{ language.translate("Play_addrandom") }}',
            pickYourNumbersBtn    : '{{ language.translate("Play_picknumber") }}',
            drawsSectionTitle     : '{{ language.translate('tittle_multiple') }}',
            drawsSectionSubtitle  : '{{ language.translate('multiple_discount') }}',
            mobTicketRandomizeBtn : '{{ language.translate("Play_randomize") }}',
            mobTicketClearBtn     : '{{ language.translate('clear_btn') }}',
            mobTicketSubmitBtn    : '{{ language.translate("Play_donebtn") }}',
            powerPlayCheck        : '{{ language.translate("mega_play_checkbox") }}',
            powerPlayInfo         : '{{ language.translate("mega_play_info") }}',
            powerballLabel        : '{{ language.translate("megaball") }}',
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

{% block body %}

    <main id="content">

        <div class="megamillions--page">

            <div class="top-banner--megamillions"></div>

            <div class="wrapper">

                <h1 class="play--h1">
                    {% if mobile == 1 %}
                        {#{{ language.translate("megamillions_mobile_h1") }}#}
                        Online
                    {% else %}
                        {#{{ language.translate("megamillions_h1") }}#}
                        Online
                    {% endif %}
                </h1>

                {% include "_elements/megamillions-header.volt" %}

                <div class="gameplay" id="gameplay"></div>

                {% include "_elements/megamillions-bottom-block.volt" %}

            </div>

        </div>




        {#TODO: remove temp files please#}
        {% include "_elements/_temp_megamillions-results.volt" %}
        {% include "_elements/_temp_megamillions-history.volt" %}


    </main>

{% endblock %}