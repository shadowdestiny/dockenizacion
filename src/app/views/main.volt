<!DOCTYPE html>
<html>
	<head>
		<title>{{ title }}</title>
        {% block template_scripts %}{% endblock %}
	</head>
	<body>
        <h1>Main layout</h1>
        {{ var }}
        {% block body %}{% endblock %}
	</body>
</html>