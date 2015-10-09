<div class="box-threshold cl">
    <input id="threshold" class="checkbox" data-role="none" type="checkbox">
    <label for="threshold" class="details">
        <span class="txt">{{ language.translate("When Jackpot reach") }}</span>
        <span class="input-value hidden">
            &euro;
            <input type="text" placeholder="{{ language.translate('Insert value') }} ">
        </span>
        <select class="threshold">
            <option title="{{ language.translate('aprox. $49 millions') }}">{{ language.translate('50 millions &euro;') }}</option>
            <option value="default" selected="selected" title="{{ language.translate('aprox. $74 millions') }}">{{ language.translate('75 millions &euro;') }}</option>
            <option title="{{ language.translate('aprox. $99 millions') }}">{{ language.translate('100 millions &euro;') }}</option>
            <option value="choose">{{ language.translate('Choose threshold') }}</option>
        </select>
        <span class="txt type">{{ language.translate("play the chosen numbers") }}</span>
    </label>
</div>