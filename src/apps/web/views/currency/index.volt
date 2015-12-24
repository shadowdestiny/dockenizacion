{% extends "main.volt" %}
{% block template_css %}
<style>
.currency .box-basic{margin-bottom:2em;}
.currency .list{margin-right:-2%;}
.currency .list a{text-decoration:none; display:block; padding:5px 10px; border:1px solid #EFC048; border-radius:5px;}
.currency .list a:hover{background:#EFC048; color:#fff;}
.currency .list li{float:left; margin:0 2% 1em 0; width:18%;}
.currency .list .active{background:#AE5279; color:#fff; border:1px solid #710045;}
.currency .list .active:hover{cursor:default;}
.currency .curr{display:inline-block; width:100px; font-size:125%; font-weight:bold;}
.currency .name{display:block;}

@media only screen and (max-width:992px){
    .currency .list a{min-height:74px;}
}
@media only screen and (max-width:768px){
    .currency .list li{width:31%;}
    .currency .list a{min-height:auto;}
}

@media only screen and (max-width:500px){/*Not standard size*/
    .currency .list a{min-height:74px;}
}        

@media only screen and (max-width:480px){
    .currency .list li{width:48%; text-align:left;}
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