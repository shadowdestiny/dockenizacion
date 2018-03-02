{% extends "main.volt" %}
{% block bodyClass %}contact{% endblock %}
{% block header %}
    {% set activeNav='{"myClass": ""}'|json_decode %}
    {% include "_elements/header.volt" %}
{% endblock %}
{% block template_scripts %}
    <script src="/w/js/mobileFix.min.js"></script>{% endblock %}

{% block template_css %}

    <link Rel="Canonical" href="{{ postData.getCanonical() }}" />

{% endblock %}

{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}

{% block body %}
    <main id="content">
        <div class="blog--page">
            <div class="banner"></div>

            <div class="wrapper">

                <div class="title-block">
                    <h1>
                        {{ postData.getTitle() }}
                    </h1>
                </div>

                <div class="content">
                    <div class="wrapper">
                        {{ postData.getContent() }}
                    </div>
                </div>
            </div>
        </div>
    </main>
{% endblock %}
