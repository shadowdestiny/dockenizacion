{% extends "main.volt" %}
{% block bodyClass %}contact{% endblock %}
{% block header %}
    {% set activeNav='{"myClass": ""}'|json_decode %}
    {% include "_elements/header.volt" %}
{% endblock %}
{% block template_scripts %}
    <script src="/w/js/mobileFix.min.js"></script>{% endblock %}

{% block template_css %}

    <link Rel="Canonical" href="{{ language.translate('canonical_contact') }}" />

{% endblock %}

{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}

{% block body %}
    <main id="content" class="">
        <div class="wrapper">
            {{ dump(postsBlog) }}
        </div>
    </main>
{% endblock %}
