{% extends "main.volt" %}
{% block template_css %}
{#<link rel="stylesheet" href="/w/css/home.css">
#}
{% endblock %}

{% block bodyClass %}login{% endblock %}

{% block meta %}<title>Log in - Euromillions Admin System</title>{% endblock %}

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
                {{ form('/admin/login/index') }}
                    <div class="module-head">
                        <h3>Log In</h3>
                    </div>
                    <div class="module-body">

                        {% if errors %}
                            <div class="alert alert-danger">
                                <strong>Error</strong><br>
                                <span class="txt">{% for error in errors %}{{ error }}<br>{% endfor %}</span>
                            </div>
                        {%  endif %}
                        <div class="control-group">
                            <div class="controls row-fluid">
                                <input class="span12" type="text" id="inputEmail" name="username" placeholder="Username">
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="controls row-fluid">
                                <input class="span12" type="password" id="inputPassword" name="password" placeholder="Password">
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
               {{ endform() }}
            </div>
        </div>
    </div>
</div>
{% endblock %}