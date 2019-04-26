{% extends "../../shared/views/howtoplay/index.volt" %}

{% block template_css %}
    <link rel="stylesheet" href="/w/css/faq.css">
    <!--[if IE 9]><style>.laurel{display:none;}</style><![endif]-->
    <link Rel="Canonical" href="{{ language.translate('canonical_megamillions_howto') }}" />
{% endblock %}
{% block body %}

    <main id="content">

        <div class="faq--page" data-ajax="false">
            <div class="banner"></div>

            <div class="wrapper">

                <div class="title-block">
                    <h1>
                        <span class="resizeme">
                            {{ language.translate("howToMS_h1") }}
                        </span>
                    </h1>
                </div>

                <div class="content">

                    {% include "../../shared/views/_elements/section-powerball.volt" %}

                    <div class="left-section faq-section">

                        <div class="answer">

                            <div class="accordion-block-outer">

                                <h2 class="h2 yellow">{{ language.translate("howToMS_h2_1") }}</h2>

                                <div class="accordion-block-outer--contet">
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("howToMS_1q") }}</h3>
                                        <p>{{ language.translate("howToMS_1a") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("howToMS_2q") }}</h3>
                                        <p>{{ language.translate("howToMS_2a") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("howToMS_3q") }}</h3>
                                        <p>{{ language.translate("howToMS_3a") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("howToMS_4q") }}</h3>
                                        <p>{{ language.translate("howToMS_4a") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("howToMS_5q") }}</h3>
                                        <p>{{ language.translate("howToMS_5a") }}</p>
                                    </div>
                                </div>
                            </div>


                            <div class="accordion-block-outer">

                                <h2 class="h2 yellow">{{ language.translate("howToMS_h2_2") }}</h2>

                                <div class="accordion-block-outer--contet">
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("howToMS_6q") }}</h3>
                                        <p>{{ language.translate("howToMS_6a") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("howToMS_7q") }}</h3>
                                        <p>{{ language.translate("howToMS_7a") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("howToMS_8q") }}</h3>
                                        <p>{{ language.translate("howToMS_8a") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("howToMS_9q") }}</h3>
                                        <p>{{ language.translate("howToMS_9a") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("howToMS_10q") }}</h3>
                                        <p>{{ language.translate("howToMS_10a") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("howToMS_11q") }}</h3>
                                        <p>{{ language.translate("howToMS_11a") }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-block-outer">
                                <h2 class="h2 yellow">{{ language.translate("howToMS_h2_3") }}</h2>

                                <div class="accordion-block-outer--contet">
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("howToMS_12q") }}</h3>
                                        <p>{{ language.translate("howToMS_12a") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("howToMS_13q") }}</h3>
                                        <p>{{ language.translate("howToMS_13a") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("howToMS_14q") }}</h3>
                                        <p>{{ language.translate("howToMS_14a") }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-block-outer">
                                <h2 class="h2 yellow">{{ language.translate("howToMS_h2_4") }}</h2>

                                <div class="accordion-block-outer--contet">
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("howToMS_15q") }}</h3>
                                        <p>{{ language.translate("howToMS_15a") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("howToMS_16q") }}</h3>
                                        <p>{{ language.translate("howToMS_16a") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("howToMS_17q") }}</h3>
                                        <p>{{ language.translate("howToMS_17a") }}</p>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

            </div>
        </div>
    </main>
{% endblock %}