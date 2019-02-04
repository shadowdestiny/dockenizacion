	<div class="countdown">
		<div class="day unit">
			<span class="val">%-d {% if show_s_days == '1' %}{{ language.translate("nextDraw_oneday") }}{% else %}{{ language.translate("nextDraw_day") }}{% endif %}</span>
		</div>
		<div class="dots">:</div>
		<div class="hour unit">
			<span class="val">%-H {{ language.translate("nextDraw_hr") }}</span>
		</div>
		<div class="dots">:</div>
		<div class="minute unit">
			<span class="val">%-M {{ language.translate("nextDraw_min") }}</span>
		</div>
        {% if show_s_days == '0' %}
		<div class="dots">:</div>
		<div class="seconds unit">
			<span class="val">%-S {{ language.translate("nextDraw_sec") }}</span>
		</div>
		{% endif %}
	</div>