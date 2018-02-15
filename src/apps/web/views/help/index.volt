{% extends "main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/w/css/faq.css">
    <!--[if IE 9]><style>.laurel{display:none;}</style><![endif]-->
    <link Rel="Canonical" href="{{ language.translate('canonical_euromillions_faq') }}" />
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
                        {{ language.translate("help_head") }}
                    </h1>
                </div>

                <div class="content">

                    {% include "_elements/section-powerball.volt" %}

                    <div class="left-section faq-section">

                        <div class="answer">

                            <div class="accordion-block-outer">

                                <h2 class="h2 yellow">{{ language.translate("help_subhead") }}</h2>

                                <div class="accordion-block-outer--contet">
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("step1_subhead") }}</h3>
                                        <p>{{ language.translate("step1_text") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("step2_subhead") }}</h3>
                                        <p>{{ language.translate("step2_text") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("step3_subhead") }}</h3>
                                        <p>{{ language.translate("step3_text") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("step4_subhead") }}</h3>
                                        <p>{{ language.translate("step4_text") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("step5_subhead") }}</h3>
                                        <p>{{ language.translate("step5_text") }}</p>
                                    </div>
                                </div>
                            </div>


                            <div class="accordion-block-outer">

                                <h2 class="h2 yellow">{{ language.translate("help_secondH2") }}</h2>

                                <div class="accordion-block-outer--contet">
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("eurom_no1_q") }}</h3>
                                        <p>{{ language.translate("eurom_no1_a") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("eurom_no15_q") }}</h3>
                                        <p>{{ language.translate("eurom_no15_a") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("eurom_no25_q") }}</h3>
                                        <p>{{ language.translate("eurom_no25_a") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("eurom_no9_q") }}</h3>
                                        <p>{{ language.translate("eurom_no9_a") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("eurom_no14_q") }}</h3>
                                        <p>{{ language.translate("eurom_no14_a") }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-block-outer">
                                <h2 class="h2 yellow">{{ language.translate("help_thirdH2") }}</h2>

                                <div class="accordion-block-outer--contet">
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("eurom_no4_q") }}</h3>
                                        <p>{{ language.translate("eurom_no4_a") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("eurom_no17_q") }}</h3>
                                        <p>{{ language.translate("eurom_no17_a") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("eurom_no6_q") }}</h3>
                                        <p>{{ language.translate("eurom_no6_a") }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-block-outer">
                                <h2 class="h2 yellow">{{ language.translate("help_fourthH2") }}</h2>

                                <div class="accordion-block-outer--contet">
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("eurom_no5_q") }}</h3>
                                        <p>{{ language.translate("eurom_no5_a") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("eurom_no7_q") }}</h3>
                                        <p>{{ language.translate("eurom_no7_a") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("eurom_no8_q") }}</h3>
                                        <p>{{ language.translate("eurom_no8_a") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("eurom_no18_q") }}</h3>
                                        <p>{{ language.translate("eurom_no18_a") }}</p>
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