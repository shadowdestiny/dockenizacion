{% extends "main.volt" %}
{% block template_css %}
{#<link rel="stylesheet" href="/w/css/home.css">
#}
{% endblock %}

{% block bodyClass %}login{% endblock %}

{% block meta %}<title>Log in - Euromillions Admin System</title>{% endblock %}

{% block header %}
{% set activeNav='{"myClass": ""}'|json_decode %} {# It need to be empty #}
{% include "_elements/header.volt" %}
{% endblock %}

{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}

{% block body %}
<div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <a class="brand" href="http://www.euromillions.com"><img src="/a/img/euromillions.png" alt="Euromillions"></a>
        </div>
    </div>
</div>

<div class="wrapper">
    <div class="container">
        <div class="row">
            <div class="module module-login span4 offset4">
                <form class="form-vertical">
                    <div class="module-head">
                        <h3>Log In</h3>
                    </div>
                    <div class="module-body">
                        <div class="control-group">
                            <div class="controls row-fluid">
                                <input class="span12" type="text" id="inputEmail" placeholder="Username">
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="controls row-fluid">
                                <input class="span12" type="password" id="inputPassword" placeholder="Password">
                            </div>
                        </div>
                    </div>
                    <div class="module-foot">
                        <div class="control-group">
                            <div class="controls clearfix">
                                <button type="submit" class="btn btn-primary pull-right">Access</button>
                                <label class="checkbox">
                                    <input type="checkbox"> Remember me
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{% endblock %}