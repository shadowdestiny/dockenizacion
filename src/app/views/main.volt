<!DOCTYPE html>
<html>
	<head>
        {% include "_elements/meta.volt" %}
        <!-- javascript analytics pre -->
        <!-- favicon -->
        <!-- javascript generic -->
        <!-- css generic -->
        {% block template_scripts %}{% endblock %}
        {% block template_css %}{% endblock %}
	</head>
	<body>
        {% include "_elements/header.volt" %}
        {% block body %}{% endblock %}
        {% include "_elements/footer.volt" %}
    </body>
</html>