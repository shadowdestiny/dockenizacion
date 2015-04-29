<!DOCTYPE html>
<html>
	<head>
        <!--META-->
        <!-- javascript analytics pre -->
        <!-- favicon -->
        <!-- javascript generic -->
        <!-- css generic -->
        {% block template_scripts %}{% endblock %}
        {% block template_css %}{% endblock %}
	</head>
	<body>
    <!-- header (include) -->
        {% block body %}{% endblock %}
    <!-- footer (include) -->
    </body>
    <!-- javascript analytics post -->
    <!-- javascript post body -->

</html>