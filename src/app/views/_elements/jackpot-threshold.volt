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
                <option value="default" selected="selected" title="{{ language.translate('aprox. $74 millions') }}">{{ language.translate('75 millions &euro;') }}</option>
                <option title="{{ language.translate('aprox. $99 millions') }}">{{ language.translate('100 millions &euro;') }}</option>
                <option value="choose">{{ language.translate('Choose threshold') }}</option>
            </select>
        </div>
        <span class="txt type">{{ language.translate("play the chosen numbers") }}</span>
    </div>
</div>