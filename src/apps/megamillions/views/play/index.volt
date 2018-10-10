{% extends "../../shared/views/play/index.volt" %}

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

                {% include "../../shared/views/_elements/_megamillions/megamillions-header.volt" %}

                {% include "../../shared/views/_elements/_megamillions/megamillions-bottom-block.volt" %}

            </div>

        </div>

    </main>

{% endblock %}