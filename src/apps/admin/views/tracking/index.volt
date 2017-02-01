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
    function deleteConfirmation(id)
    {
        if (confirm('Are you sure you want to remove this TC from database?')) {
            location='/admin/tracking/deleteTrackingCode?id='+id;
        }
    }

    function editTrackingCode(id, name, description)
    {
        $('#showEditTC').css('display', 'block');
        $('#editIdTC').val(id);
        $('#editNameTC').val(name);
        $('#editDescriptionTC').val(description);
    }

    function cloneTrackingCode(id, name, description)
    {
        $('#showCloneTC').css('display', 'block');
        $('#cloneIdTC').val(id);
        $('#cloneNameTC').val(name);
        $('#cloneDescriptionTC').val(description);
    }

    function downloadUsers(id)
    {
        location='/admin/tracking/downloadUsers?id='+id;
    }

    function launchTrackingCode(id)
    {
        location='/admin/tracking/launchTrackingCode?id='+id;
    }

    function editPreferences(id)
    {
        location='/admin/tracking/editPreferences?id='+id;
    }
</script>
    <div class="wrapper">
        <div class="container">
            <div class="module">
                <div class="module-body">
                    <h1 class="h1 purple">Tracking Codes</h1>
                    <p><b class="purple">Create new Tracking Code</b></p>
                    {% if errorMessage is not empty %}
                        <p>{{ errorMessage }}</p>
                    {% endif %}
                    <form method="post" action="/admin/tracking/createTrackingCode">
                        <table>
                            <thead>
                            <tr>
                                <td>Name</td>
                                <td>Description</td>
                                <td></td>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <input type="text" name="name" />
                                    </td>
                                    <td>
                                        <input type="text" name="description" />
                                    </td>
                                    <td>
                                        <input type="submit" class="btn btn-primary" value="Create" />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                    <div id="showEditTC" style="display:none;">
                        <p><b class="purple">Edit Tracking Code</b></p>
                        <form method="post" action="/admin/tracking/editTrackingCode">
                            <table>
                                <thead>
                                <tr>
                                    <td>Name</td>
                                    <td>Description</td>
                                    <td></td>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        <input type="hidden" name="id" id="editIdTC" />
                                        <input type="text" name="name" id="editNameTC" />
                                    </td>
                                    <td>
                                        <input type="text" name="description" id="editDescriptionTC" />
                                    </td>
                                    <td>
                                        <input type="submit" class="btn btn-primary" value="Save" />
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>
                    <div id="showCloneTC" style="display:none;">
                        <p><b class="purple">Clone Tracking Code</b></p>
                        <form method="post" action="/admin/tracking/cloneTrackingCode">
                            <table>
                                <thead>
                                <tr>
                                    <td>Name</td>
                                    <td>Description</td>
                                    <td></td>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        <input type="hidden" name="id" id="cloneIdTC" />
                                        <input type="text" name="name" id="cloneNameTC" />
                                    </td>
                                    <td>
                                        <input type="text" name="description" id="cloneDescriptionTC" />
                                    </td>
                                    <td>
                                        <input type="submit" class="btn btn-primary" value="Clone" />
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>
                    <br />
                    <p><b class="purple">Tracking Codes</b></p>
                    {% if trackingCodes is not empty %}
                        <table class="table" width="100%">
                            <thead>
                            <tr class="special">
                                <th width="20%">Name</th>
                                <th width="40%">Description</th>
                                <th width="5%">Users</th>
                                <th width="35%">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            {% for trackingCode in trackingCodes %}
                                <tr>
                                    <td>
                                        {{ trackingCode['name'] }}
                                    </td>
                                    <td>
                                        {{ trackingCode['description'] }}
                                    </td>
                                    <td>
                                        {{ trackingCode['num_users'] }}
                                    </td>
                                    <td>
                                        <input type="button" class="btn btn-primary" value="Edit"  onClick="editTrackingCode({{ trackingCode['id'] }}, '{{ trackingCode['name'] }}', '{{ trackingCode['description'] }}');" />
                                        <input type="button" class="btn btn-primary" value="Delete" onClick="deleteConfirmation({{ trackingCode['id'] }});" />
                                        <input type="button" class="btn btn-primary" value="Clone"  onClick="cloneTrackingCode({{ trackingCode['id'] }}, '{{ trackingCode['name'] }}', '{{ trackingCode['description'] }}');" />
                                        <input type="button" class="btn btn-primary" value="Download"  onClick="downloadUsers({{ trackingCode['id'] }});" />
                                        <input type="button" class="btn btn-primary" value="Launch"  onClick="launchTrackingCode({{ trackingCode['id'] }});" />
                                        <input type="button" class="btn btn-primary" value="Preferences"  onClick="editPreferences({{ trackingCode['id'] }});" />
                                    </td>
                                </tr>
                            {% endfor %}
                            </tbody>
                        </table>
                    {% else %}
                        We don't have tracking codes yet, create one.
                    {% endif %}
                </div>
            </div>
        </div>
    </div>

{% endblock %}