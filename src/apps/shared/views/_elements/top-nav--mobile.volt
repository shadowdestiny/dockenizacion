{% if user_logged is empty %}

<div class="sign-block xxs--max">
    <ul class="ul-top-nav">
        {% include "../../shared/views/_elements/sign.volt" %}
    </ul>
</div>

{% else %}
{% endif %}