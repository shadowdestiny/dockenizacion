{% extends "main.volt" %}
{% block template_css %}
<style>
    .currency .box-basic{margin-bottom:2em;}
    .currency .list a:link{text-decoration:none; display:block; padding:5px 10px; border:1px solid #fff;}
    .currency .list a:hover{background:#ffc; border:1px solid #f00;}
    .currency .list li{float:left; margin-bottom:1em; width:20%;}
    .currency .curr{display:inline-block; width:100px; font-size:125%; font-weight:bold;}
    .currency .name{display:block;}

    @media only screen and (max-width:768px){
        .currency .list li{width:33%;}
    }

    @media only screen and (max-width:480px){
        .currency .list li{width:50%;  text-align:left;}
    }
</style>
{% endblock %}
{% block bodyClass %}currency{% endblock %}

{% block header %}
    {% set activeNav='{"myClass": ""}'|json_decode %}
    {% include "_elements/header.volt" %}
{% endblock %}
{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}

{% block body %}
<main id="content">
    <div class="wrapper">
        <div class="box-basic">
            <h1 class="h2">Choose your currency</h1>

            <ul class="no-li list cl">
            {% for currency in currency_list %}
                <li><a href="/ajax/user-settings/setCurrencyReload/{{currency.code}}"><span class="curr">{{ currency.code }}</span> <span class="name">{{ currency.name }}</span></a></li>
            {% endfor %}
            </ul>
        </div>
    </div>
</main>
{% endblock %}