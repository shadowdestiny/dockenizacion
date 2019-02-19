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
    function editFeature(name, description, status)
    {
        $('#showEdit').css('display', 'block');
        $('#editName').val(name);
        $('#editDescription').val(description);
        $('#editStatus').val(status);
    }
</script>
    <div class="wrapper">
        <div class="container">
            <div class="module">
                <div class="module-body">
                    <h1 class="h1 purple">Manage Features</h1>

                    {% if errorMessage is not empty and errorMessage is not '' %}
                        <p>{{ errorMessage }}</p>
                    {% endif %}

                    <div id="showEdit" style="display:none;">
                        <p><b class="purple">Edit Feature</b></p>
                        <form method="post" action="/admin/featureFlagApi/editFeature">
                            <table>
                                <thead>
                                <tr>
                                    <td>Name</td>
                                    <td>Description</td>
                                    <td>Status</td>
                                </tr>
                                </thead>
                                <tr>
                                    <td>
                                        <input type="text" name="name" id="editName" readonly />
                                    </td>
                                    <td>
                                        <input  type="tex" name="description" id="editDescription" />
                                    </td>
                                    <td>
                                        <select class="select" name="status" id="editStatus" >
                                            <option value="1">ON</option>
                                            <option value="0">OFF</option>
                                        </select>
                                    </td>
                                </tr>
                                </tr>
                                    <td colspan="2">
                                        <input type="button" class="left btn btn-danger" value="Cancel">
                                    </td>
                                    <td align="right">
                                        <input type="submit" class="btn btn-primary" value="Save" />
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>

                    <p><b class="purple">Features List</b></p>

                    {% if features is not empty %}
                        <table class="table" width="100%">
                            <thead>
                            <tr class="special">
                                <th width="20%">Name</th>
                                <th width="40%">Description</th>
                                <th width="5%">Status</th>
                                <th>Updated</th>
                                <th>Created</th>
                                <td></td>
                            </tr>
                            </thead>
                            <tbody>
                            {% for feature in features %}
                                <tr>
                                    <td>
                                        {{ feature.getName() }}
                                    </td>
                                    <td>
                                        {{ feature.getDescription() }}
                                    </td>
                                    <td>
                                        {% if feature.getStatus() %}
                                            <div style="background-color: green; text-align: center;">ON</div>
                                        {% else %}
                                            <div style="background-color: red; text-align: center;">OFF</div>
                                        {% endif %}
                                    </td>
                                    <td>
                                        {{ date('d-m-y G:i:s', feature.getUpdatedAt()) }}
                                    </td>
                                    <td>
                                        {{ date('d-m-y G:i:s', feature.getCreatedAt()) }}

                                    </td>
                                    <td>
                                        <input type="button" class="btn btn-primary" value="Edit"  onClick="editFeature('{{ feature.getName() }}', '{{ feature.getDescription() }}', '{{ feature.getStatus(false) }}');" />
                                    </td>
                                </tr>
                            {% endfor %}
                            </tbody>
                        </table>
                    {% else %}
                        We don't have features yet.
                    {% endif %}
                </div>
            </div>
        </div>
    </div>
{% endblock %}