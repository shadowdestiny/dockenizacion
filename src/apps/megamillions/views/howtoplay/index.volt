{% extends "main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/w/css/faq.css">
    <!--[if IE 9]><style>.laurel{display:none;}</style><![endif]-->
    <link Rel="Canonical" href="{{ language.translate('canonical_powerball_howto') }}" />
{% endblock %}
{% block bodyClass %}faq{% endblock %}

{% block header %}
    {#{% set activeNav='{"myClass": ""}'|json_decode %}#}
    {% set activeNav='{"myClass": "help"}'|json_decode %} {# It need to be empty #}
    {% include "_elements/header.volt" %}
{% endblock %}
{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}
{% block template_scripts %}
    <script src="/w/js/mobileFix.min.js"></script>
{% endblock %}
{% block template_scripts_code %}
    {# EMTD we use this function as workaround from jquery mobile to anchor link via url #}

    $(function(){
    var html_formatted_offset = [];
    $('.countdown .dots').eq(2).hide();
    $('.countdown .seconds').hide();
    var element = $('.countdown');
    var html_formatted = element.html();
    $('.countdown .dots').eq(2).show();
    $('.countdown .seconds').show();
    $('.countdown .day').remove();
    $('.countdown .dots').eq(0).remove();
    html_formatted_offset[0] = $('.countdown').html();
    $('.countdown .hour').remove();
    $('.countdown .dots').eq(0).remove();
    html_formatted_offset[1] = $('.countdown').html();
    $('.countdown .minute').remove();
    $('.countdown .dots').eq(0).remove();
    html_formatted_offset[2] = $('.countdown').html();
    var finish_action = function(){
    $('.box-next-draw .btn.red').remove();
    }
    var date = '{{ date_draw }}'; {#  To test "2015/11/17 10:49:00"  #}
    var finish_text = "<div class='closed'>{{ language.translate('The Draw is closed') }}</div>";
    count_down(element,html_formatted,html_formatted_offset, date,finish_text, finish_action);
    });
{% endblock %}
{% block body %}

    <main id="content">

        <div class="faq--page" data-ajax="false">
            <div class="banner"></div>

            <div class="wrapper">

                <div class="title-block">
                    <h1>
                        <span class="resizeme">
                            {{ language.translate("h1_howto_pow") }}
                        </span>
                    </h1>
                </div>

                <div class="content">

                    {% include "_elements/section-powerball.volt" %}

                    <div class="left-section faq-section">

                        <div class="answer">

                            <div class="accordion-block-outer">

                                <h2 class="h2 yellow">{{ language.translate("h2_howto_pow1") }}</h2>

                                <div class="accordion-block-outer--contet">
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("pow_step1_head") }}</h3>
                                        <p>{{ language.translate("pow_step1_text") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("pow_step2_head") }}</h3>
                                        <p>{{ language.translate("pow_step2_text") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("pow_step3_head") }}</h3>
                                        <p>{{ language.translate("pow_step3_text") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("pow_step4_head") }}</h3>
                                        <p>{{ language.translate("pow_step4_text") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("pow_step5_head") }}</h3>
                                        <p>{{ language.translate("pow_step5_text") }}</p>
                                    </div>
                                </div>
                            </div>


                            <div class="accordion-block-outer">

                                <h2 class="h2 yellow">{{ language.translate("h2_howto_pow2") }}</h2>

                                <div class="accordion-block-outer--contet">
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("howto_pow_1q") }}</h3>
                                        <p>{{ language.translate("howto_pow_1a") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("howto_pow_2q") }}</h3>
                                        <p>{{ language.translate("howto_pow_2a") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("howto_pow_3q") }}</h3>
                                        <p>{{ language.translate("howto_pow_3a") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("howto_pow_4q") }}</h3>
                                        <p>{{ language.translate("howto_pow_4a") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("howto_pow_5q") }}</h3>
                                        <p>{{ language.translate("howto_pow_5a") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("howto_pow_6q") }}</h3>
                                        <p>{{ language.translate("howto_pow_6a") }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-block-outer">
                                <h2 class="h2 yellow">{{ language.translate("h2_howto_pow3") }}</h2>

                                <div class="accordion-block-outer--contet">
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("howto_pow_7q") }}</h3>
                                        <p>{{ language.translate("howto_pow_7a") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("howto_pow_8q") }}</h3>
                                        <p>{{ language.translate("howto_pow_8a") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("howto_pow_9q") }}</h3>
                                        <p>{{ language.translate("howto_pow_9a") }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-block-outer">
                                <h2 class="h2 yellow">{{ language.translate("h2_howto_pow4") }}</h2>

                                <div class="accordion-block-outer--contet">
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("howto_pow_10q") }}</h3>
                                        <p>{{ language.translate("howto_pow_10a") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("howto_pow_11q") }}</h3>
                                        <p>{{ language.translate("howto_pow_11a") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("howto_pow_12q") }}</h3>
                                        <p>{{ language.translate("howto_pow_12a") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("howto_pow_13q") }}</h3>
                                        <p>{{ language.translate("howto_pow_13a") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("howto_pow_14q") }}</h3>
                                        <p>{{ language.translate("howto_pow_14a") }}</p>
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