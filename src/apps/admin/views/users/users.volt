{% extends "main.volt" %}

{% block template_scripts %}
    <script>{% include "users/users.js" %}</script>
{% endblock %}
{% block bodyClass %}users{% endblock %}

{% block meta %}<title>Tanslation Overview - Euromillions Admin System</title>{% endblock %}

{% block header %}
    {% set activeNav='{"myClass": ""}'|json_decode %} {# It need to be empty #}
    {% include "_elements/header.volt" %}
{% endblock %}

{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}

{% block body %}
    <div class="wrapper">
    <div class="container">
    <div class="module">
    <div class="module-body">

    <h1 class="h1 purple">Registered Users</h1>
    <form class="cl search">
        <div class="left">
            <label for="search">Search by</label>
            <select class="select">
                <option>Surname</option>
                <option>Email</option>
                <option>Telephone</option>
                <option>Residence</option>
            </select>
            <input id="search" class="input" type="text">
            <a href="#" class="btn btn-primary right add">Search</a>
        </div>

        <a href="#" class="btn btn-primary right add">Add New User</a>
    </form>

    <table class="table">
    <thead>
    <tr class="special">
        <th class="name">Name</th>
        <th class="surname">Surname</th>
        <th class="contact">Contact detail</th>
        <th class="residence">Residence</th>
        <th class="wallet">Balance</th>
        <th class="action">Actions</th>
    </tr>
    </thead>
    <tbody>

    {% if users is empty %}
    {% else %}
        {%  for user in users %}
            <tr>
                <td class="name">{{ user.name}}</td>
                <td class="surname">{{ user.surname }}</td>
                <td class="contact">
                    <a href="mailto:{{ user.email }}">{{ user.email }}</a>
                    <br>{{ user.phone_number }}
                </td>
                <td class="residence">
                    {{ user.city }}, {{ user.zip }}, {{ user.country }}
                    <br>{{ user.street }}
                </td>
                <td class="wallet">
                    <strong>Wallet:</strong> &euro; {{ user.balance }}
                    <br><strong>Winning:</strong> &euro; 0
                </td>
                <td class="action">
                    <a href="#" class="btn btn-danger">Delete</a>
                    {# <a href="#" class="btn btn-success">View Transactions</a> #}
                    <a href="#" data-id="{{ user.id }}" class="btn btn-primary">Edit</a>
                </td>
            </tr>
        {% endfor %}
    {% endif %}
            </tbody>
            </table>
            /* Insert paging functionality for the table above */

            <div class="hidden-element crud-user">
                <h2 class="sub-title purple"></h1>
                    {% include "_elements/registration.volt" %}
            </div>
                {#
                 /* View Transactions */
                <h2 class="sub-title purple">View Transactions</h1>
                <div class="row-fluid">
                    <span class="span6">
                        <strong>Name</strong>: Mario Rossi 
                    </span>
                    <span class="span6">
                        <strong>Contact Details</strong>: <a href="mailto:robert.ciao@gmail.com">robert.ciao@gmail.com</a> - +36 257 850 952                   
                    </span>
                </div>

                <table class="table">
                    <thead>
                        <tr class="special">
                            <th class="date">Date Played</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                ddd
                            </td>
                        </tr>
                    </tbody>
                </table>
                #}
            </div>
            </div>
            </div>
            </div>
            {# Alessio, please, feel free to change div modal#}
            <div class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Modal title</h4>
                        </div>
                        <div class="modal-body">
                            <p>One fine body&hellip;</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
            {% endblock %}
