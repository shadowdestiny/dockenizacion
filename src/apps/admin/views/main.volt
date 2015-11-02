<!DOCTYPE html>
<html>
    <head>
        {% include "_elements/meta.volt" %} {# META tags #}
        {% block meta %}{% endblock %}

    {#  JS 
        for old browsers (use SHIV or Modernizr)
    #}

        {# CSS Compress this css in a single file #}
        <link type="text/css" href="/a/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link type="text/css" href="/a/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
        <link rel="stylesheet" href="/a/css/theme.css">
        <link rel="stylesheet" href="/a/css/euromillions.css">
        <link type="text/css" href="/a/images/icons/css/font-awesome.css" rel="stylesheet">
        {% block template_css %}{% endblock %}      {# Inject unique css #}

        {% include "_elements/js-lib.volt" %} {# JS libraries #}
        {% block template_scripts %}{% endblock %}  {# Inject unique js #}

        {# FONTS  #}
        <link rel="stylesheet" href='http://fonts.googleapis.com/css?family=Open+Sans:400,400italic,700'>
    </head>

    <body class="{% block bodyClass %}{% endblock %}">
       
        {% block header %}{% endblock %}
        {% block body %}{% endblock %}
        {% block footer %}{% endblock %}

    </body>
</html>