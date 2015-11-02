{% extends "main.volt" %}

{% block bodyClass %}user-profile{% endblock %}

{% block meta %}<title>Account - Euromillions Admin System</title>{% endblock %}

{% block header %}
{% set activeNav='{"myClass": ""}'|json_decode %} {# It need to be empty #}
{% include "_elements/header.volt" %}
{% endblock %}

{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}

{% block body %}
 <div class="wrapper">
    <div class="container">
        <div class="content">
            <h1 class="h1 purple">User profile</h1>
            <div class="module">
                <div class="module-body">
                    <div class="media-body">
                        <div class="alert alert-success">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <strong>Password changed successfully</strong>
                        </div>
                        <div class="alert alert-danger">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <strong>Error</strong>
                        </div>
                        <div class="row-fluid">
                            <div class="span6">
                                <div class="box">
                                    <span class="name">Username:</span>
                                    <span class="value">myemail@email.com</span>
                                </div>
                                <div class="box">
                                    <span class="name">User type:</span>
                                    <span class="value">Business, News</span>
                                </div>
                            </div>
                            <div class="span6">
                                <a href="#" class="btn btn-primary">Change Password</a>
                                <br>/* Show only when button is being pressed */
                                <label for="old-psw">Old Password</label>
                                <input id="old-psw" class="span8" type="password">
                                <label for="new-psw">New Password</label>
                                <input id="new-psw" class="span8" type="password">
                                <label for="cnf-psw">Confirm Password</label>
                                <input id="cnf-psw" class="span8" type="password">
                                <div class="cl">
                                    <a class="btn btn-inverse left" href="#">Cancel</a>
                                    <a class="btn btn-primary right" href="#">Update Password</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
{% endblock %}