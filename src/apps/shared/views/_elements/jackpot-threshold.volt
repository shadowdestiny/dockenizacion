{# This element has his own SCSS file #}
{% block template_scripts_code %}
function resetSelect(){
    var mySelect = $(".threshold option:selected").text();
    $(".box-threshold .select-txt").text(mySelect)
    $(".box-threshold .styled-select").addClass("disabled")
    $(".threshold").prop('disabled', 'disabled');
}

function showSelect(){
    $(".input-value").hide();
    $(".threshold").show().val("default");
    $(".box-threshold .styled-select").show()
}

function toggleSelect(){
    $("#threshold").on('click',function(){
        if($(this).is(":checked")){
            $(".box-threshold .styled-select").removeClass("disabled")
            $(".threshold").prop('disabled', false);
        }else{
            $(".box-threshold .styled-select").addClass("disabled")
            $(".threshold").prop('disabled', 'disabled');
            resetSelect()
        }
        if($(".input-value").is(":visible")){
            showSelect()
            resetSelect()
        }
    });
}

function checkOption(){
    $(".threshold").change(function(){
        if($(this).val() == 'choose'){ {# // if you want to specify the jackpot threshold #}
            $(".input-value").show();
            $(this).hide();
            $(".box-threshold .styled-select").hide()
        }
    });
}

$(function(){
    resetSelect()
    toggleSelect()
    checkOption()
});
{% endblock %}

<div class="box-threshold cl">
    <input id="threshold" class="checkbox" data-role="none" type="checkbox">
    <div class="details">
        <span class="txt">{{ language.translate("When Jackpot reach") }}</span>
        <span class="input-value hidden">
            &euro;
            <input type="text" value="85000000" placeholder="{{ language.translate('Insert numeric value') }}">
        </span>
        <div class="styled-select">
            <div class="select-txt"></div>
            <select class="threshold mySelect">
                <option title="{{ language.translate('aprox. $49 millions') }}">{{ language.translate('50 millions &euro;') }}</option>
                <option value="default" selected="selected" title="{{ language.translate('aprox. &dollar;74 millions') }}">{{ language.translate('75 millions &euro;') }}</option>
                <option title="{{ language.translate('aprox. &dollar;99 millions') }}">{{ language.translate('100 millions &euro;') }}</option>
                <option value="choose">{{ language.translate('Choose threshold') }}</option>
            </select>
        </div>
        <span class="txt type">{{ language.translate("play the chosen numbers") }}</span>
    </div>
</div>