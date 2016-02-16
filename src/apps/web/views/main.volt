<!DOCTYPE html>
<html>
    <head>
        {% include "_elements/meta.volt" %} {# META tags #}

        {# CSS Compress this css in a single file #}
        <link rel="stylesheet" href="/w/css/main.css">
        {% block template_css %}{% endblock %}      {# Inject unique css #}

        {# FONTS  #}
        <link rel="stylesheet" href='https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,700'>
    </head>

	<body class="{% block bodyClass %}{% endblock %}">
        {% block modal %}{% endblock %}

        <div data-role="page" id="main-page">
            {% block header %}{% endblock %}

            <div data-role="content">
                {% block body %}{% endblock %}
            </div>
            {% block footer %}{% endblock %}
        </div>

        {# EDTD To remove SUBNAV when not connected as account #}
        {% block mobileNav %}
            {% if user_logged %}
                <div data-role="page" id="nav-account">
                    <ul class="no-li" data-role="listview">
                        <li><a href="/account" data-transition="slide" data-direction="reverse">{{ language.translate("Account") }}</a></li>
                        <li><a href="/account/games" data-transition="slide" data-direction="reverse">{{ language.translate("Games") }}</a></li>
                        <li><a href="/account/wallet" data-transition="slide" data-direction="reverse">{{ language.translate("Wallet") }}</a></li>
                        <li><a href="/account/transaction" data-transition="slide" data-direction="reverse">{{ language.translate("Transactions") }}</a></li>
                        <li><a href="/account/email" data-transition="slide" data-direction="reverse">{{ language.translate("Email Settings") }}</a></li>
                        <li><a href="/account/password" data-transition="slide" data-direction="reverse">{{ language.translate("Change Password") }}</a></li>
                        <li><a href="/logout" data-transition="slide" data-direction="reverse">{{ language.translate("Sign out") }}</a></li>
                    </ul>
                </div>
            {% endif %}

            <div data-role="page" id="language">
                <ul class="no-li" data-role="listview">
                    {% for currency in currency_list %}
                        <li><a href="#main-page" onclick="globalFunctions.setCurrency('{{ currency.code }}')" data-transition="slide" data-direction="reverse">{{ currency.name }}</a></li>
                    {% endfor %}
                </ul>
            </div>
        {% endblock %}

        <div class="ending">
            {{ language.translate("The draw will close in 30 minutes.") }}
        </div>

        <svg>
            {# Chrome need this #}
            {# White gradient logo #}
            <linearGradient id="d" gradientUnits="userSpaceOnUse" x1="16.777" y1="-135.945" x2="16.777" y2="-161.627" gradientTransform="translate(21.248 882.618) scale(4.104)"><stop offset="0" stop-color="#FFD936"/><stop offset="1" stop-color="#FFF"/></linearGradient>
            {# Shadow to any svg #}
            <linearGradient id="e" gradientUnits="userSpaceOnUse" x1="31.034" y1="2.99" x2="31.034" y2="53.503"><stop offset="0" stop-color="#FEFAEC"/><stop offset="1" stop-color="#F1D86F"/></linearGradient>
        </svg>

        {% include "_elements/js-lib.volt" %} {# JS libraries #}
        {% block template_scripts %}{% endblock %}  {# Inject unique js #}
        <script>
        {% block template_scripts_code %}{% endblock %}  {# Inject unique js inside <script> tag #}
        </script>
        {% block template_scripts_after %}{% endblock %}
    </body>
</html>