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

        <div class="blog--page" data-ajax="false">
            <div class="banner"></div>

            <div class="wrapper">

                <div class="title-block">
                    <h1>
                        {{ language.translate("H1_blogindex") }}
                    </h1>
                </div>

                <div class="content">
        <div class="wrapper">
            {{ dump(postsBlog) }}
        </div>
    </main>
{% endblock %}
