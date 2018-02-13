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
                        {{ language.translate("eurom_head") }}
                    </h1>
                </div>
                <div class="content">
                    {% include "_elements/section-powerball.volt" %}
                    <div class="left-section faq-section">
                        <div class="answer">
                            <div class="accordion-block-outer">

                                <h2 class="h2 yellow">{{ language.translate("eurom_subhead_basics") }}</h2>

                                <div class="accordion-block-outer--contet">
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("eurom_no2_q") }}</h3>
                                        <p>{{ language.translate("eurom_no2_a") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("eurom_no3_q") }}</h3>
                                        <p>{{ language.translate("eurom_no3_a") }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-block-outer">
                                <h2 class="h2 yellow">{{ language.translate("eurom_subhead_winnings") }}</h2>
                                <div class="accordion-block-outer--contet">
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("eurom_no11_q") }}</h3>
                                        <p>{{ language.translate("eurom_no11_a") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("eurom_no12_q") }}</h3>
                                        <p>{{ language.translate("eurom_no12_a") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("eurom_no13_q") }}</h3>
                                        <p>{{ language.translate("eurom_no13_a") }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-block-outer">
                                <h2 class="h2 yellow">{{ language.translate("eurom_subhead_account") }}</h2>

                                <div class="accordion-block-outer--contet">
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("eurom_no16_q") }}</h3>
                                        <p>{{ language.translate("eurom_no16_a") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("eurom_no19_q") }}</h3>
                                        <p>{{ language.translate("eurom_no19_a") }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-block-outer">
                                <h2 class="h2 yellow">{{ language.translate("eurom_subhead_trouble") }}</h2>

                                <div class="accordion-block-outer--contet">
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("eurom_no20_q") }}</h3>
                                        <p>{{ language.translate("eurom_no20_a",['email_support':email_support]) }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("eurom_no21_q") }}</h3>
                                        <p>{{ language.translate("eurom_no21_a",['email_support':email_support]) }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("eurom_no22_q") }}</h3>
                                        <p>{{ language.translate("eurom_no22_a") }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-block-outer">
                                <h2 class="h2 yellow">{{ language.translate("eurom_subhead_legal") }}</h2>

                                <div class="accordion-block-outer--contet">
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("eurom_no24_q") }}</h3>
                                        <p>{{ language.translate("eurom_no24_a") }}</p>
                                    </div>
                                    <div class="accordion-block">
                                        <h3 class="h3">{{ language.translate("eurom_no23_q") }}</h3>
                                        <p>{{ language.translate("eurom_no23_a") }}</p>
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