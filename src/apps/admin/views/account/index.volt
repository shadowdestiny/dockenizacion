{% extends "main.volt" %}

{% block bodyClass %}user-profile{% endblock %}

{% block meta %}<title>Account - Euromillions Admin System</title>{% endblock %}

{% block header %}
{% set activeNav='{"myClass": ""}'|json_decode %} {# It need to be empty #}
{% include "_elements/header.volt" %}
{% endblock %}

{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}

{% block body %}
<script language="javascript">
    function showChangePassword()
    {
        $('#showChangePassword').show();
    }

    function hideChangePassword()
    {
        $('#showChangePassword').hide();
    }

    function validatePasswords()
    {
        if (document.getElementById('cnf-psw').value != document.getElementById('new-psw').value) {
            alert('Passwords are not equals');
            return false;
        }
        return true;
    }
</script>
 <div class="wrapper">
    <div class="container">
        <div class="content">
            <div class="module">
                <div class="module-body">
                    <h1 class="h1 purple">User profile</h1>
                    <div class="media-body">
                        {% if errorMessage is not empty %}
                            {% if errorMessage == 'OK' %}
                                <div class="alert alert-success">
                                    <button data-dismiss="alert" class="close" type="button">×</button>
                                    <strong>Password changed successfully</strong>
                                </div>
                            {% else %}
                                <div class="alert alert-danger">
                                    <button data-dismiss="alert" class="close" type="button">×</button>
                                    <strong>Error: The old password is not correct</strong>
                                </div>
                            {% endif %}
                        {% endif %}
                        <div class="row-fluid">
                            <div class="span6">
                                <div class="box">
                                    <span class="name">User:</span>
                                    <span class="value">{{ userAdmin.getName() }} {{ userAdmin.getSurname() }}</span>
                                </div>
                                <div class="box">
                                    <span class="name">Email:</span>
                                    <span class="value">{{ userAdmin.getEmail() }}</span>
                                </div>
                            </div>
                            <div class="span6">
                                <a href="#" class="btn btn-primary" onclick="showChangePassword()">Change Password</a>
                                <div id="showChangePassword" style="display:none;">
                                    <form action="/admin/account/index" method="post" onSubmit="return validatePasswords()">
                                        <input type="hidden" name="email" value="{{ userAdmin.getEmail() }}" />
                                        <input type="hidden" name="changePassword" value="1" />
                                        <label for="old-psw">Old Password</label>
                                        <input id="old-psw" name="old-psw" class="span8" type="password" />
                                        <label for="new-psw">New Password</label>
                                        <input id="new-psw" name="new-psw" class="span8" type="password" />
                                        <label for="cnf-psw">Confirm Password</label>
                                        <input id="cnf-psw" name="cnf-psw" class="span8" type="password" />
                                        <div class="cl">
                                            <a class="btn btn-inverse left" href="#" onclick="hideChangePassword()">Cancel</a>
                                            <input type="submit" class="btn btn-primary right" value="Update Password" />
                                        </div>
                                    </form>
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