{% extends "main.volt" %}

{% block template_css %}
    <link rel="stylesheet" href="/a/css/pagination.css">
{% endblock %}

{% block bodyClass %}jackpot{% endblock %}

{% block template_scripts %}

{% endblock %}

{% block meta %}<title>Languages - Euromillions Admin System</title>{% endblock %}

{% block header %}
    {% set activeNav='{"myClass": ""}'|json_decode %} {# It need to be empty #}
    {% include "_elements/header.volt" %}
{% endblock %}

{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}
{% block body %}
    <script language="javascript">
        function deleteConfirmation(id) {
            if (confirm('Are you sure you want to remove this language from database?')) {
                location = '/admin/translation/deleteLanguage?id=' + id;
            }
        }

        function editLanguage(id, ccode, defaultLocale, active) {
            $('#showEditLanguage').css('display', 'block');
            $('#editIdLanguage').val(id);
            $('#editCountryCodeLanguage').val(ccode);
            $('#editDefaultLocaleLanguage').val(defaultLocale);
            if (active == 1) $('#editActive').prop('checked', true);
            else $('#editActive').prop('checked', false);

        }
    </script>

    <div class="wrapper">
        <div class="container">
            <div class="module">
                <div class="module-body">
                    <h1 class="h1 purple">Languages</h1>
                    <p><b class="purple">Add a new Language</b></p>
                    {% if errorMessage is not empty %}
                        <p>{{ errorMessage }}</p>
                    {% endif %}
                    <form method="post" action="/admin/translation/createLanguage">
                        <table>
                            <thead>
                            <tr>
                                <td>Country code</td>
                                <td>Locale code</td>
                                <td>Active</td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    <input type="text" name="ccode"/>
                                </td>
                                <td>
                                    <input type="text" name="defaultLocale"/>
                                </td>
                                <td width="20px">
                                    <input type="checkbox" name="active" />
                                </td>
                                <td>
                                    <input type="submit" class="btn btn-primary" value="Create"/>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </form>
                    <div id="showEditLanguage" style="display:none;">
                        <p><b class="purple">Edit Language</b></p>
                        <form method="post" action="/admin/translation/editLanguage">
                            <table>
                                <thead>
                                <tr>
                                    <td>Country code</td>
                                    <td>Default Locale</td>
                                    <td>Active</td>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        <input type="hidden" name="id" id="editIdLanguage"/>
                                        <input type="text" name="ccode" id="editCountryCodeLanguage"/>
                                    </td>
                                    <td>
                                        <input type="text" name="defaultLocale" id="editDefaultLocaleLanguage"/>
                                    </td>
                                    <td width="20px">

                                        <input type="checkbox" name="active" id="editActive" />
                                    </td>
                                    <td>
                                        <input type="submit" class="btn btn-primary" value="Save"/>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>
                    {% if languagesList is not empty %}
                        <table class="table">
                            <thead>
                            <tr class="special">
                                <th>ID</th>
                                <th>Country Code</th>
                                <th>Default Locale</th>
                                <th>Active</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            {% for language in languagesList %}
                                <tr>
                                    <td>
                                        {{ language.getId() }}
                                    </td>
                                    <td>
                                        {{ language.getCcode() }}
                                    </td>
                                    <td>
                                        {{ language.getDefaultLocale() }}
                                    </td>
                                    <td>
                                        {% if (language.getActive() is not 0) %}
                                            Yes
                                        {% else %}
                                            No
                                        {% endif %}
                                    </td>
                                    <td>
                                        <input type="button" class="btn btn-primary" value="Edit"
                                               onClick="editLanguage({{ language.getId() }}, '{{ language.getCcode() }}', '{{ language.getDefaultLocale() }}', '{{ language.getActive() }}');"/>
                                        <input type="button" class="btn btn-primary" value="Delete"
                                               onClick="deleteConfirmation({{ language.getId() }});"/>
                                    </td>
                                </tr>
                            {% endfor %}
                            </tbody>
                        </table>
                    {% else %}
                        Not languages yet.
                    {% endif %}
                </div>
            </div>
        </div>
    </div>
{% endblock %}