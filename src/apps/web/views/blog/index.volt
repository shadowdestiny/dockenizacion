{% extends "main.volt" %}
{% block bodyClass %}contact{% endblock %}
{% block header %}
    {% set activeNav='{"myClass": ""}'|json_decode %}
    {% include "_elements/header.volt" %}
{% endblock %}
{% block template_scripts %}
    <script src="/w/js/mobileFix.min.js"></script>{% endblock %}

{% block template_css %}
    <link Rel="Canonical" href="{{ language.translate('canonical_blogindex') }}" />
{% endblock %}

{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}

{% block body %}
    <main id="content">

        <div class="blog--page">
            <div class="banner"></div>

            <div class="wrapper">

                <div class="title-block">
                    <h1>
                        {{ language.translate("H1_blogindex") }}
                    </h1>
                </div>

                <div class="content">
                    {{ language.translate("intro_blogindex") }}
                    <hr />
                    <div class="wrapper">
                        {% if postsBlog is not empty %}
                            <table>
                                <tr>
                                    {% set cont = 0 %}
                                    {% for post in postsBlog %}
                                        <td>
                                            <img src="{{ post.getImage() }}" />
                                            <h3>{{ post.getTitle() }}</h3>
                                            <p>{{ post.getDescription() }}</p>
                                        </td>
                                        {% set cont = (cont + 1) %}
                                        {% if (cont % 2) == 0 %}</tr><tr>{% endif %}
                                    {% endfor %}
                                </tr>
                            </table>
                        {% else %}
                            We don't have posts yet.
                        {% endif %}
                    </div>
                </div>
            </div>
        </div>
    </main>
{% endblock %}
