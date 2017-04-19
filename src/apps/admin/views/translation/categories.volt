{% extends "main.volt" %}

{% block template_css %}{% endblock %}

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
            if (confirm('Are you sure you want to remove this category from database?')) {
                location='/admin/translation/deleteCategory?id='+id;
            }
        }

        function editTrackingCode(id, name, code, description)
        {
            $('#showEditCategory').css('display', 'block');
            $('#editIdCategory').val(id);
            $('#editNameCategory').val(name);
            $('#editCodeCategory').val(code);
            $('#editDescriptionCategory').val(description);
        }
    </script>
    <div class="wrapper">
        <div class="container">
            <div class="module">
                <div class="module-body">
                    <h1 class="h1 purple">Categories</h1>
                    {% if errorMessage is not empty %}
                        <p>{{ errorMessage }}</p>
                    {% endif %}
                    <form method="post" action="/admin/translation/createCategory">
                        <table>
                            <thead>
                            <tr>
                                <td>Name</td>
                                <td>Code</td>
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
                                    <input type="text" name="code" />
                                </td>
                                <td>
                                    <input type="text" name="description" />
                                </td>
                                <td>
                                    <input type="submit" class="btn btn-primary" value="Add Category" />
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </form>
                    <div id="showEditCategory" style="display:none;">
                        <p><b class="purple">Edit Category</b></p>
                        <form method="post" action="/admin/translation/editCategory">
                            <table>
                                <thead>
                                <tr>
                                    <td>Name
                                    <td>Code</td>
                                    <td>Description</td>
                                    <td></td>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        <input type="hidden" name="id" id="editIdCategory" />
                                        <input type="text" name="name" id="editNameCategory" />
                                    </td>
                                    <td>
                                        <input type="text" name="code" id="editCodeCategory" />
                                    </td>
                                    <td>
                                        <input type="text" name="description" id="editDescriptionCategory" />
                                    </td>
                                    <td>
                                        <input type="submit" class="btn btn-primary" value="Save" />
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>
                    {% if categoriesList is not empty %}
                        <table class="table">
                            <thead>
                            <tr class="special">
                                <th>ID</th>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            {% for category in categoriesList %}
                                <tr>
                                    <td>
                                        {{ category.getId() }}
                                    </td>
                                    <td>
                                        {{ category.getCategoryName() }}
                                    </td>
                                    <td>
                                        {{ category.getCategoryCode() }}
                                    </td>
                                    <td>
                                        {{ category.getDescription() }}
                                    </td>
                                    <td>
                                        <input type="button" class="btn btn-primary" value="Edit"  onClick="editTrackingCode({{ category.getId() }}, '{{ category.getCategoryName() }}', '{{ category.getCategoryCode() }}', '{{ category.getDescription() }}');" />
                                        <input type="button" class="btn btn-primary" value="Delete" onClick="deleteConfirmation({{ category.getId() }});" />
                                    </td>
                                </tr>
                            {% endfor %}
                            </tbody>
                        </table>
                    {% else %}
                        Not categories yet.
                    {% endif %}
                </div>
            </div>
        </div>
    </div>
{% endblock %}