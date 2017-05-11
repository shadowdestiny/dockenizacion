{% extends "main.volt" %}

{% block template_css %}
    <link rel="stylesheet" href="/a/css/pagination.css">
{% endblock %}

{% block bodyClass %}jackpot{% endblock %}

{% block template_scripts %}

{% endblock %}

{% block meta %}<title>Translation - Euromillions Admin System</title>{% endblock %}

{% block header %}
    {% set activeNav='{"myClass": ""}'|json_decode %} {# It need to be empty #}
    {% include "_elements/header.volt" %}
{% endblock %}

{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}
{% block body %}

    <script language="javascript">
        function createKeyConfirmation()
        {
            if (document.createKeyForm.key.value === "") {
                alert('You must fill a Key');
                return false;
            }

            return true;
        }

        function editKey(key, description)
        {
            $('#showEditKey').css('display', 'block');
            $('#editKeyCode').val(key);
            $('#editOldKeyCode').val(key);
            $('#editKeyCategoryId').val($('#categoryIdToShow').val());
            $('#editKeyDescription').val(description);
        }

        function deleteConfirmation(key)
        {
            if (confirm('Are you sure you want to remove this Translation Key from database?')) {
                location='/admin/translation/deleteKey?key='+key;
            }
        }

        $(function () {
            $("#searchKeys").click(function(){
                $.ajax({
                    url: 'translationKeysResult',
                    type: 'POST',
                    data: {
                        key: $("#keyToShow").val(),
                        categoryId: $("#categoryIdToShow").val(),
                    },
                    success: function(data) {
                        $('#translationResults').html(data);
                    },
                    error: function() {
                        alert('Something went wrong, please try again.');
                    },
                });
            });
        });
    </script>
    <div class="wrapper">
        <div class="container">
            <div class="module">
                <div class="module-body">
                    <h1 class="h1 purple">Translation</h1>
                    {% if errorMessage is not empty %}
                        <p>{{ errorMessage }}</p>
                    {% endif %}
                    <form name="createKeyForm" method="post" action="/admin/translation/createKey" onsubmit="return createKeyConfirmation();">
                        <table>
                            <thead>
                            <tr>
                                <td>Key</td>
                                <td>Category</td>
                                <td>Description</td>
                                <td></td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    <input type="text" name="key" />
                                </td>
                                <td>
                                    <select name="categoryId">
                                        {% if categoriesList is not empty %}
                                            {% for category in categoriesList %}
                                                <option value="{{ category.getId() }}">{{ category.getCategoryName() }}</option>
                                            {% endfor %}
                                        {% endif %}
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="description" />
                                </td>
                                <td>
                                    <input type="submit" class="btn btn-primary" value="Add Key" />
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </form>
                    <div id="showEditKey" style="display:none;">
                        <p><b class="purple">Edit Key</b></p>
                        <form method="post" action="/admin/translation/editKey">
                            <table>
                                <thead>
                                <tr>
                                    <td>Key
                                    <td>Category</td>
                                    <td>Description</td>
                                    <td></td>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        <input type="text" name="key" id="editKeyCode" />
                                        <input type="hidden" name="oldKey" id="editOldKeyCode" />
                                    </td>
                                    <td>
                                        <select name="categoryId" id="editKeyCategoryId">
                                            {% if categoriesList is not empty %}
                                                {% for category in categoriesList %}
                                                    <option value="{{ category.getId() }}">{{ category.getCategoryName() }}</option>
                                                {% endfor %}
                                            {% endif %}
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="description" id="editKeyDescription" />
                                    </td>
                                    <td>
                                        <input type="submit" class="btn btn-primary" value="Save" />
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>
                    <hr />
                    <table>
                        <thead>
                        <tr>
                            <td>Key</td>
                            <td>Category</td>
                            <td></td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <input type="text" name="keyToShow" id="keyToShow" />
                            </td>
                            <td>
                                <select name="categoryIdToShow" id="categoryIdToShow">
                                    {% if categoriesList is not empty %}
                                        {% for category in categoriesList %}
                                            <option value="{{ category.getId() }}">{{ category.getCategoryName() }}</option>
                                        {% endfor %}
                                    {% endif %}
                                </select>
                            </td>
                            <td>
                                <input type="button" class="btn btn-primary" value="Search Keys" id="searchKeys" />
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div id="translationResults"></div>
                </div>
            </div>
        </div>
    </div>

{% endblock %}