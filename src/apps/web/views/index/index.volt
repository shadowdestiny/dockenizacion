{% extends "main.volt" %}
{% block template_css %}
	<link rel="stylesheet" href="/w/css/home.css">
	<link Rel="Canonical" href="{{ language.translate('canonical_home') }}" />
{% endblock %}

{% block bodyClass %}
    home
    {% include "_elements/jlength.volt" %}
{% endblock %}

{% block header %}
    <a id="top"></a>
    {% set activeNav='{"myClass": ""}'|json_decode %} {# It need to be empty #}
    {% include "_elements/header.volt" %}
{% endblock %}
{% block font %}
    <link href='https://fonts.googleapis.com/css?family=Signika:700' rel='stylesheet' type='text/css'>{% endblock %}
{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}
{% block template_scripts %}
    <script src="/w/js/mobileFix.js"></script>
    {% if ga_code is defined %}
        <!--start PROD imports
        <script src="/w/js/dist/GASignUpLanding.min.js"></script>
        end PROD imports-->
        <!--start DEV imports-->
        <script src="/w/js/GASignUpLanding.js"></script>
        <!--end DEV imports-->
    {% endif %}
{% endblock %}

{% block template_scripts_code %}
    {# EMTD we use this function as workaround from jquery mobile to anchor link via url #}
    $(function(){
    var hash = window.location.hash;
    var elem = hash.split('#')[1];
    console.log(elem);
    var scrollTop = typeof elem == 'undefined' ? 0 : $('#'+hash.split('#')[1]).offset().top;
    $(document.body).animate({
    'scrollTop': scrollTop
    }, 100);

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

        {% include "_elements/banner.volt" %}

        <div class="wrapper">
            {% include "_elements/top-nav--mobile.volt" %}

            {#{% include "_elements/christmas-lottery-banner-block.volt" %}#}

            <div class="hiw-block--section">
                <h2 class="h2">
                    {{ language.translate("banner2_head") }}
                </h2>

                <div class="hiw-block--inner">
                    <div class="hiw-block hiw-block--01">
                        <div class="hiw-block--corner">1</div>
                        <div class="hiw-block--center"></div>
                        <div class="hiw-block--description">
                            {{ language.translate("banner2_text1") }}
                        </div>
                    </div>
                    <div class="hiw-block hiw-block--02">
                        <div class="hiw-block--corner">2</div>
                        <div class="hiw-block--center"></div>
                        <div class="hiw-block--description">
                            {{ language.translate("banner2_text2") }}
                        </div>
                    </div>
                    <div class="hiw-block hiw-block--03">
                        <div class="hiw-block--corner">3</div>
                        <div class="hiw-block--center"></div>
                        <div class="hiw-block--description">
                            {{ language.translate("banner2_text3") }}
                        </div>
                    </div>
                    <div class="hiw-block hiw-block--04">
                        <div class="hiw-block--corner">4</div>
                        <div class="hiw-block--center"></div>
                        <div class="hiw-block--description">
                            {{ language.translate("banner2_text4") }}
                        </div>
                    </div>
                </div>

            </div>
            <div class="box-basic box-result">

                <div class="result--block">
                    <div class="result--block--header">

                        <h2 class="h2">{{ language.translate("results_head") }}</h2>

                        <div class="view-more-block">
                            <div class="view-more-block--inner">
                                <a href="/{{ language.translate('link_euromillions_results') }}"
                                   class="view-more-a">
                                    {{ language.translate("results_link") }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="result--block--content">
                        {% if euromillions_results is defined %}
                            {% for euromillions_result in euromillions_results %}
                                <div class="result--line">
                                    <p>
                                        {% set dateFormat = language.translate('dateformat') %}
                                        <?php
                                            $day = $euromillions_result["draw_date"]->format('l');
                                            $date = $euromillions_result["draw_date"]->format($dateFormat);
                                        ?>
                                        {{ language.translate(day) }}, {{ date }}
                                    </p>
                                    <ul class="no-li inline numbers small">
                                        {% for regular_number in euromillions_result["regular_numbers"] %}
                                            <li>{{ regular_number }}</li>
                                        {% endfor %}
                                        {% for lucky_number in euromillions_result["lucky_numbers"] %}
                                            <li class="star">{{ lucky_number }}</li>
                                        {% endfor %}

                                    </ul>
                                </div>
                            {% endfor %}
                        {% endif %}
                    </div>
                </div>

                <div class="number-generator--block">
                    <div class="number-generator--block--img">
                        <img src="/w/img/home/result-block/desktop/number-generator.jpg" alt="Latest News">
                    </div>
                    <h2 class="number-generator--block--title h2">
                        {{ language.translate("cta1_head") }}
                    </h2>
                    <h3 class="number-generator--block--subtitle">
                        {{ language.translate("cta1_subhead") }}
                    </h3>
                    <div class="number-generator--block--body">
                        {{ language.translate("cta1_text") }}
                    </div>

                    <a href="{{ language.translate("link_euromillions_play") }}"
                       class="number-generator--block--button btn-theme--big">
                        {{ language.translate("cta1_btn") }}
                    </a>
                </div>

            </div>
        </div>

        <div class="latest-news">
            <div class="wrapper">
                <div class="news-block">
                    <div class="news-block--content">
                        <h3 class="news-block--title">
                            {{ language.translate("cta2_sub1") }}
                        </h3>
                        <div class="news-block--body">
                            {{ language.translate("cta2_text1") }}
                        </div>
                    </div>
                </div>

                <div class="news-block">
                    <div class="news-block--content">
                        <h3 class="news-block--title">
                            {{ language.translate("cta2_sub2") }}
                        </h3>
                        <div class="news-block--body">
                            {{ language.translate("cta2_text2") }}
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {#{% include "_elements/latest-news.volt" %}#}

    </main>
	<script type="application/ld+json">
	{
		"@context": "http://schema.org/",
		"@type": "Organization",
		"name": "EuroMillions.com",
		"url": "{{ language.translate('markup_org_url') }}",
		"logo": "https://euromillions.com/w/img/logo/v2/logo-desktop.png",
		"contactPoint": [
			{ "@type": "ContactPoint",
			"email": "support@euromillions.com",
			"contactType": "{{ language.translate('markup_org_contact') }}",
			"availableLanguage" : [{{ language.translate('markup_org_lang') }}]
		}],
		"sameAs" : [ "https://www.facebook.com/Euromillionscom-204411286236724/",
		"https://twitter.com/_lotteries",
		"https://plus.google.com/+Euromillionscom",
		"https://www.instagram.com/euromillions.com_/",
		"https://www.linkedin.com/company/euromillions-com"
	]}
</script>
{% endblock %}