<div class="right-section">
    <div class="section-powerball">
        <section class="section-01">
            <div class="corner"></div>
            <div class="title">
                {{ language.translate("nextDraw_Estimate") }}
            </div>
            <div class="price{% if jackpot_value|length > 4 %}-sm{% endif %}">
                {{ jackpot_value }}
            </div>
            <div class="measure">
                {% if milliards %}
                    {{ language.translate("billion") }}
                {% elseif trillions %}
                    {{ language.translate("trillion") }}
                {% else %}
                    {{ language.translate("million") }}
                {% endif %}
            </div>
            <div class="title">
                {{ language.translate("nextDraw_lbl") }}
            </div>
            <div class="timer">
                {% include "_elements/countdown.volt" %}
            </div>

            <div class="btn-row">
                <a href="/{{ language.translate("link_euromillions_play") }}"
                   class="btn-theme--big">
                    <span class="resizeme">
                        {{ language.translate("nextDraw_btn") }}
                    </span>
                </a>
            </div>
        </section>

        <?php echo EuroMillions\web\components\tags\PowerBallWidgetTag::getWidget(); ?>
    </div>
</div>