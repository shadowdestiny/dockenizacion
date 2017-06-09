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
        function deleteUser(id)
        {
            if (confirm('Are you sure you want to remove this User?')) {
                location='/admin/usersadmin/deleteUser?id='+id;
            }
        }

        function editUser(id, name, surname, email, useraccess)
        {
            $('#showEditUserAdmin').css('display', 'block');
            $('#editId').val(id);
            $('#editName').val(name);
            $('#editSurname').val(surname);
            $('#editEmail').val(email);
            $('#editUseraccess').val(useraccess);
        }
    </script>
    <div class="wrapper">
        <div class="container">
            <div class="module">
                <div class="module-body">
                    <h1 class="h1 purple">Admin Users</h1>
                    <p><b class="purple">Create User</b></p>
                    {% if errorMessage is not empty %}
                        <p>{{ errorMessage }}</p>
                    {% endif %}
                    <form method="post" action="/admin/usersadmin/createUserAdmin">
                        <table>
                            <thead>
                            <tr>
                                <td>Name</td>
                                <td>Surname</td>
                                <td>Email</td>
                                <td>Password</td>
                                <td>User Access</td>
                                <td></td>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <input type="text" name="name" style="width: 80px;" />
                                    </td>
                                    <td>
                                        <input type="text" name="surname" style="width: 80px;" />
                                    </td>
                                    <td>
                                        <input type="text" name="email" style="width: 160px;" />
                                    </td>
                                    <td>
                                        <input type="password" name="password" style="width: 80px;" />
                                    </td>
                                    <td>
                                        <select name="useraccess" style="width: 80px;">
                                            <option value="A">Admin</option>
                                            <option value="U">User</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="submit" class="btn btn-primary" value="Create" />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                    <div id="showEditUserAdmin" style="display:none;">
                        <p><b class="purple">Edit User</b></p>
                        <form method="post" action="/admin/usersadmin/editUserAdmin">
                            <table>
                                <thead>
                                <tr>
                                    <td>Name</td>
                                    <td>Surname</td>
                                    <td>Email</td>
                                    <!-- td>Password</td -->
                                    <td>User Access</td>
                                    <td></td>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        <input type="hidden" name="id" id="editId" />
                                        <input type="text" name="name" id="editName" style="width: 80px;" />
                                    </td>
                                    <td>
                                        <input type="text" name="surname" id="editSurname" style="width: 80px;" />
                                    </td>
                                    <td>
                                        <input type="text" name="email" id="editEmail" style="width: 160px;" />
                                    </td>
                                    <!-- td>
                                        <input type="password" name="password" style="width: 80px;" />
                                    </td -->
                                    <td>
                                        <select name="useraccess" id="editUseraccess" style="width: 80px;">
                                            <option value="A">Admin</option>
                                            <option value="U">User</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="submit" class="btn btn-primary" value="Save" />
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>
                    <br />
                    <p><b class="purple">Users</b></p>
                    {% if adminUsers is not empty %}
                        <table class="table" width="100%">
                            <thead>
                            <tr class="special">
                                <th>Id</th>
                                <th>Name</th>
                                <th>Surname</th>
                                <th>Email</th>
                                <th>User Acess</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            {% for adminUser in adminUsers %}
                                <tr>
                                    <td>
                                        {{ adminUser.getId() }}
                                    </td>
                                    <td>
                                        {{ adminUser.getName() }}
                                    </td>
                                    <td>
                                        {{ adminUser.getSurname() }}
                                    </td>
                                    <td>
                                        {{ adminUser.getEmail() }}
                                    </td>
                                    <td>
                                        {{ adminUser.getUseraccess() }}
                                    </td>
                                    <td>
                                        <input type="button" class="btn btn-primary" value="Edit"  onClick="editUser({{ adminUser.getId() }}, '{{ adminUser.getName() }}', '{{ adminUser.getSurname() }}', '{{ adminUser.getEmail() }}', '{{ adminUser.getUseraccess() }}');" />
                                        <input type="button" class="btn btn-primary" value="Delete" onClick="deleteUser({{ adminUser.getId() }});" />
                                    </td>
                                </tr>
                            {% endfor %}
                            </tbody>
                        </table>
                    {% else %}
                        We don't have users yet, create one.
                    {% endif %}
                </div>
            </div>
        </div>
    </div>

{% endblock %}