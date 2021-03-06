<div class="countdown">
	<div class="day unit">
		<span class="val">%-d {% if show_s_days == '1' %}DAY{% else %}DAYS{% endif %}</span>
	</div>
	<div class="dots">:</div>
	<div class="hour unit">
		<span class="val">%-H HRS</span>
	</div>
	<div class="dots">:</div>
	<div class="minute unit">
		<span class="val">%-M MINS</span>
	</div>
    {% if show_s_days == '0' %}
		<div class="dots">:</div>
		<div class="seconds unit">
			<span class="val">%-S SECS</span>
		</div>
    {% endif %}
</div>