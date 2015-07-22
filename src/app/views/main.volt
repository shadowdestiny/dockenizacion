<!DOCTYPE html>
<html>
    <head>
        {% include "_elements/meta.volt" %} {# META tags #}

    {#  JS 
        for old browsers (use SHIV or Modernizr)
    #}
        {% include "_elements/js-lib.volt" %} {# JS libraries #}


        {# CSS Compress this css in a single file #}
        <link rel="stylesheet" href="/css/main.css">
        {% block template_css %}{% endblock %}      {# Inject unique css #}

        {# FONTS  #}
        <link rel="stylesheet" href='http://fonts.googleapis.com/css?family=Open+Sans:400,400italic,700'>

        {% block template_scripts %}{% endblock %}  {# Inject unique js #}
    </head>

	<body class="{% block bodyClass %}{% endblock %}">
        <div data-role="page" id="main-page">

            {% block header %}{% endblock %}

            <div data-role="content">
                {% block body %}{% endblock %}
            </div>

            {% block footer %}{% endblock %}

        </div>

        {# EDTD To remove SUBNAV when not connected as account #}
        <div data-role="page" id="nav-account">
            <ul class="no-li" data-role="listview">
                <li><a href="#main-page" data-transition="slide" data-direction="reverse">My Account</a></li>
                <li><a href="#main-page" data-transition="slide" data-direction="reverse">My Games</a></li>
                <li><a href="#main-page" data-transition="slide" data-direction="reverse">My Wallet</a></li>
                <li><a href="#main-page" data-transition="slide" data-direction="reverse">Messages</a></li>
                <li><a href="#main-page" data-transition="slide" data-direction="reverse">Sign out</a></li>
            </ul>
        </div> 

        <div data-role="page" id="language">
            Here language info
            <a href="#main-page" data-transition="slide" data-direction="reverse">go back</a>
        </div>

    </body>
</html>