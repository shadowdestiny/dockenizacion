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
            <h1 class="h1 title yellow">{{ language.translate("About Us") }}</h1>
            
	    <h2 class="h3 title yellow">{{ language.translate("What we do") }}</h2>
            <p>
               {{ language.translate("Euromillions.com is the first lottery based website built to work on every device and every screen size, no matter how large or small. Mobile or desktop, we will always offer you the best user experience.<br/></br>

Your time is valuable to us, so we work hard to provide you with a quick, smart, and reliable experience to play lottery online from the comfort of your home or on the go.<br/></br>

We understand what you expect from us and we assure you that your winnings are commission free and will remain so forever.") }} 
            </p>


	    <h2 class="h3 title yellow">{{ language.translate("Who we are") }}</h2>
            <p>               
		{{ language.translate("Euromillions.com is the first European transnational lottery launched in 2004.<br/></br>

We are an international team composed of experts and passionate players, and we believe that in order to provide you the best services, we need to follow three important principles: to be fast, convenient and secure.<br/></br>

We really hope that playing with us will make your dreams come true. For the less lucky ones, we hope that the thrill of imagining a life with a big lottery prize will give you some pleasant hours of day dreaming, until the day that you actually win and everything that you imagined becomes real.<br/></br>

Draws are held every Tuesday and Friday night at 20:45 CET in Paris, France.") }}                                                                                                            
            </p>
        





        </div>
    </div>
</main>
{% endblock %}

