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
    var finish_text = "
    <div class='closed'>{{ language.translate('The Draw is closed') }}</div>";
    count_down(element,html_formatted,html_formatted_offset, date,finish_text, finish_action);
    });
{% endblock %}
{% block body %}
    <main id="content">


        {#{% include "_elements/banner-old.volt" %}#}

        {#Banner section v.2 start#}

        {% include "_elements/banner.volt" %}


        {#Banner section v.2 end#}

        <div class="wrapper">
            {% include "_elements/top-nav--mobile.volt" %}

            {% include "_elements/christmas-lottery-banner-block.volt" %}

            {#TODO : remove this comments#}
            {#<div class="box-extra">#}
            {#<div class="cols">#}
            {#<div class="col6">#}
            {#<a href="/{{ language.translate('link_christmas_play') }}">#}
            {#<img src="/w/img/home/{{ language.translate('home_christmas_image_billions') }}" border=0/>#}
            {#</a>#}
            {#<div class="box-basic box-quick-play ball">#}
            {#<div class="content">#}
            {#<h1 class="h2">{{ language.translate("cta1_head") }}</h1>#}
            {#<p>{{ language.translate("cta1_text") }}</p>#}
            {#<a href="/{{ language.translate("link_euromillions_play") }}?random" class="btn blue big wide">{{ language.translate("cta1_btn") }}</a>#}
            {#</div>#}
            {#</div>#}
            {#</div>#}
            {#<div class="col6">#}
            {#<div class="box-basic box-result">#}
            {#<div class="cols">#}
            {#<div class="col8 content">#}
            {#<h1 class="h2">{{ language.translate("results_head") }}</h1>#}
            {#<p>{{ language.translate("results_text") }} {{ last_draw_date }} </p>#}
            {#<ul class="no-li inline numbers small">#}
            {#{% for regular_number in euromillions_results["regular_numbers"] %}#}
            {#<li>{{ regular_number }}</li>#}
            {#{% endfor %}#}
            {#{% for lucky_number in euromillions_results["lucky_numbers"] %}#}
            {#<li class="star">{{ lucky_number }}</li>#}
            {#{% endfor %}#}
            {#</ul>#}
            {#<a href="/{{ language.translate('link_euromillions_results') }}"#}
            {#class="lnk animate infi"><span#}
            {#class="txt">{{ language.translate("results_link") }}</span>#}
            {#<svg class="ico v-arrow-right3">#}
            {#<use xlink:href="/w/svg/icon.svg#v-arrow-right3"></use>#}
            {#</svg>#}
            {#</a>#}
            {#</div>#}
            {#<div class="col4 woman">&nbsp;</div>#}
            {#</div>#}
            {#</div>#}
            {#</div>#}
            {#</div>#}
            {#</div>#}

            <div class="hiw-block--section">
                <h2 class="h2">
                    How it works?
                </h2>

                <div class="hiw-block--inner">
                    <div class="hiw-block hiw-block--01">
                        <div class="hiw-block--corner">1</div>
                        <div class="hiw-block--center"></div>
                        <div class="hiw-block--description">
                            Choose your lottery <br>
                            & select your numbers
                        </div>
                    </div>
                    <div class="hiw-block hiw-block--02">
                        <div class="hiw-block--corner">2</div>
                        <div class="hiw-block--center"></div>
                        <div class="hiw-block--description">
                            Buy your ticket
							<span class="val">%-d day</span>
						<span class="dots"></span>
							<span class="val">%-H hrs</span>
						<span class="dots"></span>
							<span class="val">%-M min</span>
						<span class="dots"></span>
                        </div>
                    </div>
                    <div class="hiw-block hiw-block--03">
                        <div class="hiw-block--corner">3</div>
                        <div class="hiw-block--center"></div>
                        <div class="hiw-block--description">
                            Check the results
                        </div>
                    </div>
                    <div class="hiw-block hiw-block--04">
                        <div class="hiw-block--corner">4</div>
                        <div class="hiw-block--center"></div>
                        <div class="hiw-block--description">
                            Big Win
                        </div>
                    </div>
                </div>

            </div>
            <div class="box-basic box-result">

                <div class="result--block">
                    <div class="result--block--header">

                        <h1 class="h2">{{ language.translate("results_head") }}</h1>

                        <div class="view-more-block">
                            <div class="view-more-block--inner">
                                <a href="/{{ language.translate('link_euromillions_results') }}"
                                   class="view-more-a">
                                    {#TODO : paste real text here#}
                                    View more
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="result--block--content">
                        <div class="result--line">
                            <p>
                                {#{{ language.translate("results_text") }}#}
                                {{ last_draw_date }}

                            </p>
                            <ul class="no-li inline numbers small">
                                {% for regular_number in euromillions_results["regular_numbers"] %}
                                <li>{{ regular_number }}</li>
                                {% endfor %}
                                {% for lucky_number in euromillions_results["lucky_numbers"] %}
                                <li class="star">{{ lucky_number }}</li>
                                {% endfor %}

                            </ul>
                        </div>
                        <div class="result--line">
                            <p>
                                Friday, 20.01.2017
                            </p>
                            <ul class="no-li inline numbers small">
                                <li>9</li>
                                <li>12</li>
                                <li>29</li>
                                <li>39</li>
                                <li>45</li>
                                <li class="star">5</li>
                                <li class="star">12</li>
                            </ul>
                        </div>
                        <div class="result--line">
                            <p>
                                Tuesday, 17.01.2017
                            </p>
                            <ul class="no-li inline numbers small">
                                <li>9</li>
                                <li>12</li>
                                <li>29</li>
                                <li>39</li>
                                <li>45</li>
                                <li class="star">5</li>
                                <li class="star">12</li>
                            </ul>
                        </div>
                        <div class="result--line">
                            <p>
                                Friday, 14.01.2017
                            </p>
                            <ul class="no-li inline numbers small">
                                <li>9</li>
                                <li>12</li>
                                <li>29</li>
                                <li>39</li>
                                <li>45</li>
                                <li class="star">5</li>
                                <li class="star">12</li>
                            </ul>
                        </div>
                        <div class="result--line">
                            <p>
                                Tuesday, 11.01.2017
                            </p>
                            <ul class="no-li inline numbers small">
                                <li>9</li>
                                <li>12</li>
                                <li>29</li>
                                <li>39</li>
                                <li>45</li>
                                <li class="star">5</li>
                                <li class="star">12</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="number-generator--block">
                    <div class="number-generator--block--img">
                        <img src="/w/img/home/result-block/desktop/number-generator.png" alt="Latest News">
                    </div>
                    <h2 class="number-generator--block--title h2">
                        {#TODO : paste real text here#}
                        Number Generator
                    </h2>
                    <h2 class="number-generator--block--subtitle">
                        {#TODO : paste real text here#}
                        Don’t know what to play ?
                    </h2>
                    <div class="number-generator--block--body">
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut
                        labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco.
                        laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in
                        voluptate velit esse cillum dolore eu fugiat nulla pariatur.
                    </div>

                    <a href="{{ language.translate("link_euromillions_play") }}"
                       class="number-generator--block--button btn-theme--big">
                        {{ language.translate("cta2_btn") }}
                        {#play lucky dip#}
                    </a>
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
		"logo": "https://euromillions.com/w/img/logo/favicon.png",
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