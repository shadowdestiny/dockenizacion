<!DOCTYPE html>
<html>
    <head>
        {% include "_elements/meta.volt" %} {# META tags #}

    {#  JS 
        for old browsers (use SHIV or Modernizr)
    #}


        {# CSS Compress this css in a single file #}
        <link rel="stylesheet" href="/a/css/theme.css">
        <link rel="stylesheet" href="/a/css/euromillions.css">
        {% block template_css %}{% endblock %}      {# Inject unique css #}

        {# FONTS  #}
        <link rel="stylesheet" href='http://fonts.googleapis.com/css?family=Open+Sans:400,400italic,700'>
    </head>

    <body class="{% block bodyClass %}{% endblock %}">
       
        {% block header %}{% endblock %}
        {% block body %}{% endblock %}
        {% block footer %}{% endblock %}

        {% include "_elements/js-lib.volt" %} {# JS libraries #}
        {% block template_scripts %}{% endblock %}  {# Inject unique js #}
    </body>
</html>