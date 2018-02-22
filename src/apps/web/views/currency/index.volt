{% extends "main.volt" %}
{% block template_css %}
    <link Rel="Canonical" href="{{ language.translate('canonical_currency') }}" />
<style>
.currency .box-basic{margin-bottom:2em;}
.currency .list{margin-right:-2%;}
.currency .list a{text-decoration:none; display:block; padding:5px 10px; border:1px solid #EFC048; border-radius:5px;}
.currency .list a:hover{background:#EFC048; color:#fff; border:1px solid #C8A23C;}
.currency .list li{float:left; margin:0 2% 1em 0; width:18%;}
.currency .list .active{background:#AE5279; color:#fff; border:1px solid #710045;}
.currency .list .active:hover{cursor:default; background:#AE5279; border:1px solid #710045;}
.currency .list .symbol{float:right;}
.currency .curr{display:inline-block; width:100%; font-size:125%; font-weight:bold;}
.currency .name{display:block;}
.boxBAM .name{font-size:13px;}
.info-txt{color:#aaa; font-style:italic; font-size:0.75em; margin-bottom:1em;}
.h2{margin:0;}

@media only screen and (max-width:1200px){
.currency .list a{min-height:74px;}
}
@media only screen and (max-width:768px){
    .currency .list li{width:31%;}
    .currency .list a{min-height:auto;}
}

@media only screen and (max-width:624px){
    .currency .list a{min-height:74px;}
}

@media only screen and (max-width:480px){
    .currency .list li{width:48%; text-align:left;}
}

@media only screen and (max-width:334px){
    .boxBAM .name{font-size:12px;}
}

</style>
{% endblock %}
{% block bodyClass %}currency{% endblock %}

{% block header %}
    {% set activeNav='{"myClass": ""}'|json_decode %}
    {% include "_elements/header.volt" %}
{% endblock %}
{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}
{% block template_scripts %}<script src="/w/js/mobileFix.js"></script>{% endblock %}

{% block body %}
<main id="content">
    <div class="wrapper">
        <div class="currencies-page">

            <h1>{{ language.translate("choose") }}</h1>

            <div class="info-txt">{{ language.translate("choose_info") }}</div>

            <ul class="no-li list cl">
            {% for currency in currency_list %}
                <li class="box{{ currency.code }}"><a data-enhance=false href="/ajax/user-settings/setCurrencyReload/{{currency.code}}" {% if currency.code == current_currency %}  class="active" {% endif %}>
                    <span class="curr">{{ language.translate(currency.code ~ "_code") }}
                        {% if currency.code != currency.symbol %}
                            <span class="symbol">{{ currency.symbol }}</span>
                        {% endif %}
                    </span>
                    <span class="name">{{ language.translate(currency.code ~ "_name") }}</span>
                </a></li>
            {% endfor %}
            </ul>
        </div>
    </div>
</main>
{% endblock %}