<div class="previous-results desktop--only">
    <div class="previous-results--title">
        Previous results
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

        <input type="button" value="SHOW" id="show-results" class="btn-theme btn-secondary ui-link" />
    </form>
</div>