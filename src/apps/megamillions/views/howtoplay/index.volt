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
                            {{ language.translate("h1_howto_megam") }}
                        </span>
                    </h1>
                </div>

                <div class="content">

                    {% include "../../shared/views/_elements/section-powerball.volt" %}

                    <div class="left-section faq-section">

                        <div class="answer">

                            <div class="accordion-block-outer">

                                <h2 class="h2 yellow">{{ language.translate("h2_howto_megam1") }}</h2>

                                <div class="accordion-block-outer--contet">
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("megam_step1_head") }}</h3>
                                        <p>{{ language.translate("megam_step1_text") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("megam_step2_head") }}</h3>
                                        <p>{{ language.translate("megam_step2_text") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("megam_step3_head") }}</h3>
                                        <p>{{ language.translate("megam_step3_text") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("megam_step4_head") }}</h3>
                                        <p>{{ language.translate("megam_step4_text") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("megam_step5_head") }}</h3>
                                        <p>{{ language.translate("megam_step5_text") }}</p>
                                    </div>
                                </div>
                            </div>


                            <div class="accordion-block-outer">

                                <h2 class="h2 yellow">{{ language.translate("h2_howto_megam1") }}</h2>

                                <div class="accordion-block-outer--contet">
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("howto_megam_1q") }}</h3>
                                        <p>{{ language.translate("howto_megam_1a") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("howto_megam_2q") }}</h3>
                                        <p>{{ language.translate("howto_megam_2a") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("howto_megam_3q") }}</h3>
                                        <p>{{ language.translate("howto_megam_3a") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("howto_megam_4q") }}</h3>
                                        <p>{{ language.translate("howto_megam_4a") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("howto_megam_5q") }}</h3>
                                        <p>{{ language.translate("howto_megam_5a") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("howto_megam_6q") }}</h3>
                                        <p>{{ language.translate("howto_megam_6a") }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-block-outer">
                                <h2 class="h2 yellow">{{ language.translate("h2_howto_megam3") }}</h2>

                                <div class="accordion-block-outer--contet">
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("howto_megam_7q") }}</h3>
                                        <p>{{ language.translate("howto_megam_7a") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("howto_megam_8q") }}</h3>
                                        <p>{{ language.translate("howto_megam_8a") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("howto_megam_9q") }}</h3>
                                        <p>{{ language.translate("howto_megam_9a") }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-block-outer">
                                <h2 class="h2 yellow">{{ language.translate("h2_howto_megam4") }}</h2>

                                <div class="accordion-block-outer--contet">
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("howto_megam_10q") }}</h3>
                                        <p>{{ language.translate("howto_megam_10a") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("howto_megam_11q") }}</h3>
                                        <p>{{ language.translate("howto_megam_11a") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("howto_megam_12q") }}</h3>
                                        <p>{{ language.translate("howto_megam_12a") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("howto_megam_13q") }}</h3>
                                        <p>{{ language.translate("howto_megam_13a") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("howto_megam_14q") }}</h3>
                                        <p>{{ language.translate("howto_megam_14a") }}</p>
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