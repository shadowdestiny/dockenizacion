
{# This is a comment #}

<!DOCTYPE html>
<html>
	<head>
        {% include "_elements/meta.volt" %} {# META tags 
        <!-- javascript analytics pre -->
        <!-- javascript generic -->
        <!-- css generic -->

    {#  JS 
        for old browsers (use SHIV or Modernizr)
    #}

        <!--[if lt IE 9]>
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <script src="/js/vendor/picturefill.min.js" async></script>

        {# CSS Compress this css in a single file #}
        <link rel="stylesheet" href="/css/basic.css">
        <link rel="stylesheet" href="/css/column.css">
        <link rel="stylesheet" href='/css/icomoon.css'> {# Vectorial Icons loaded as fonts #}

        {# FONTS  #}
        <link rel="stylesheet" href='http://fonts.googleapis.com/css?family=Open+Sans:400,400italic,700' rel='stylesheet'>

        {% block template_scripts %}{% endblock %}  {# Inject unique js #}
        {% block template_css %}{% endblock %}      {# Inject unique css #}
	</head>
	<body>
        {% include "_elements/header.volt" %}
        {% block body %}{% endblock %}
        {% include "_elements/footer.volt" %}
    </body>
</html>