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
    <link Rel="Canonical" href="{{ postData.getCanonical() }}" />
{% endblock %}

{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}

{% block body %}
    <main id="content">
        <div class="blog--page">
            <div class="banner"></div>
            <div class="wrapper">
                <div class="content">
                    <div class="image-title-blog">
                        <img src="{{ postData.getImage() }}" {% if mobile == 1 %}width="450" {% else %}width="960"{% endif %} />
                    </div>

                    <div class="title-blog-big{% if mobile == 1 %}-mobile{% endif %}">
                        <h1>
                            {{ postData.getTitle() }}
                        </h1>
                    </div>

                    <div class="wrapper">
                        {{ postData.getContent() }}
                    </div>
                    <a href="/{{ language.translate("link_blogindex") }}" class="link-blog">{{ language.translate("gotoindex_btn") }}</a>
                </div>
            </div>
        </div>
    </main>
{% endblock %}
