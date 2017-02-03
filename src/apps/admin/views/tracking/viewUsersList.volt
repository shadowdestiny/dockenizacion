{% extends "main.volt" %}

{% block template_css %}
    <link rel="stylesheet" href="/a/css/pagination.css">
{% endblock %}

{% block bodyClass %}jackpot{% endblock %}

{% block template_scripts %}

{% endblock %}

{% block meta %}<title>El Millón - Euromillions Admin System</title>{% endblock %}

{% block header %}
    {% set activeNav='{"myClass": ""}'|json_decode %} {# It need to be empty #}
    {% include "_elements/header.volt" %}
{% endblock %}

{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}
{% block body %}
<script language="javascript">
    function deleteUserFromTrackingCode(trackingCodeId, userId)
    {
        if (confirm('Are you sure you want to remove this user from TC?')) {
            location='/admin/tracking/deleteUserFromTc?trackingCodeId='+trackingCodeId+'&userId='+userId;
        }
    }
</script>
    <div class="wrapper">
        <div class="container">
            <div class="module">
                <div class="module-body">
                    <h1 class="h1 purple">Tracking Codes</h1>
                    <p><b class="purple">List of tracking code <b>{{ trackingCode.getName() }}</b></p>
                    {% if users is not empty %}
                        <table class="table" width="100%">
                            <thead>
                            <tr class="special">
                                <th>ID</th>
                                <th>Name</th>
                                <th>Surname</th>
                                <th>email</th>
                                <th> &nbsp; </th>
                            </tr>
                            </thead>
                            {% for user in users %}
                                <tbody>
                                    <tr>
                                        <td>{{ user.getId() }}</td>
                                        <td>{{ user.getName() }}</td>
                                        <td>{{ user.getSurname() }}</td>
                                        <td>{{ user.getEmail() }}</td>
                                        <td align="center">
                                            <input type="submit" class="btn btn-primary" value="Delete" onClick="deleteUserFromTrackingCode({{ trackingCode.getId() }}, '{{ user.getId() }}');" />
                                        </td>
                                    </tr>
                                </tbody>
                            {% endfor %}
                        </table>
                    {% else %}
                        We don't have users for this tracking code.
                    {% endif %}
                    <p align="center">
                        <input type="button" value="Go Back" onclick="location='/admin/tracking/index'" class="btn btn-primary" />
                    </p>
                </div>
            </div>
        </div>
    </div>

{% endblock %}