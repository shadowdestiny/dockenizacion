<div class="previous-results desktop--only">
    <div class="previous-results--title">
        {{ language.translate("pastNumbers_title") }}
    </div>
    <form action="" class="previous-results--selectboxes">
        <div class="selectbox">
            <select name="year" id="year">
                <?php
                                                for ($i=$actual_year;$i>=2006;$i--){
                ?>
                <option value="{{ i }}">{{ i }}</option>
                <?php
                                                }
                                            ?>
            </select>
        </div>
        <div class="selectbox">
            <select name="month" id="month">
                {% for i in 1..12 %}
                    <option value="{{ i }}">{{ i }}</option>
                {% endfor %}
            </select>
        </div>
        <div class="selectbox">
            <select name="day" id="day">

            </select>
        </div>

        <div class="previous-results--button">
            <div class="btn-theme--big resizeme">
                <input type="button" value="{{ language.translate("PastResults_btn") }}" id="show-results" class="ui-link" />
            </div>

        </div>

    </form>
</div>