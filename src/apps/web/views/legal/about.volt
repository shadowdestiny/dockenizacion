{% extends "main.volt" %}
{% block template_css %}<link rel="stylesheet" href="/w/css/legal.css">{% endblock %}
{% block bodyClass %}about{% endblock %}

{% block header %}
    {% set activeNav='{"myClass":""}'|json_decode %}
    {% include "_elements/header.volt" %}
{% endblock %}
{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}
{% block template_scripts %}<script src="/w/js/mobileFix.min.js"></script>{% endblock %}

{% block body %}
<main id="content">
    <div class="wrapper">
        <div class="nav box-basic">
           {% set activeSubnav='{"myClass":"about"}'|json_decode %}
           {% include "legal/_nav.volt" %}
        </div>
        <div class="box-basic content">
            <h1 class="h1 title yellow">{{ language.translate("about_head") }}</h1>
            
            <h2 class="h3 title yellow">{{ language.translate("about_sub1") }}</h2>
            <p>{{ language.translate("about_text1") }}</p>

            <h2 class="h3 title yellow">{{ language.translate("about_sub2") }}</h2>
            <p>{{ language.translate("about_text2") }}</p>
        </div>
    </div>
</main>
{% endblock %}

