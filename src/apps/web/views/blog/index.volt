{% extends "main.volt" %}
{% block bodyClass %}{% endblock %}
{% block header %}
    {% set activeNav='{"myClass": ""}'|json_decode %}
    {% include "_elements/header.volt" %}
{% endblock %}
{% block template_scripts %}
    <script src="/w/js/mobileFix.min.js"></script>
{% endblock %}
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
                    {% if postsBlog is not empty %}
                        <table>
                            <tr>
                                {% set cont = 0 %}
                                {% for post in postsBlog %}
                                    <td align="center">
                                        <img src="{{ post.getImage() }}" width="320px" height="160" />
                                        <h2 class="title-blog">{{ post.getTitle() }}</h2>
                                        <p align="justify">{{ post.getDescription() }}</p>
                                        <p align="left"><a href="/{{ language.translate('link_blogindex') }}/{{ post.getUrl() }}" class="link-blog">READ THE POST</a></p>
                                    </td>
                                    {% if mobile == 1 %}
                                        </tr><tr>
                                    {% else %}
                                        <td> &nbsp;&nbsp;&nbsp; </td>
                                        {% set cont = (cont + 1) %}
                                        {% if (cont % 2) == 0 %}</tr><tr>{% endif %}
                                    {% endif %}
                                {% endfor %}
                            </tr>
                        </table>
                        {{ paginator_view }}
                    {% else %}
                        We don't have posts yet.
                    {% endif %}
                </div>
            </div>
        </div>
    </main>
{% endblock %}
