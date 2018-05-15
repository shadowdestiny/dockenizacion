{% extends "main.volt" %}

{% block template_css %}<link rel="stylesheet" href="/a/css/pagination.css">{% endblock %}

{% block template_scripts %}<script>{% include "users/users.js" %}</script>{% endblock %}
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
                <div class="alert alert-success hidden-element">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>Changes Saved</strong>
                </div>
                <div class="alert alert-danger hidden-element">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>Changes Failed</strong>
                </div>
           <div class="box-user-data">
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
                        <a href="javascript:void(0)" class="btn btn-primary right add">Search</a>
                    </div>
                    <a href="javascript:void(0);" class="btn btn-primary right add">Add New User</a>
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
                            <tr>
                                <td colspan="6">
                                    No data to show
                                </td>
                            </tr>
                        {% else %}
                            {%  for i,user in users %}
                                <tr>
                                    <td class="name">{{ user.name}}</td>
                                    <td class="surname">{{ user.surname }}</td>
                                    <td class="contact">
                                        <a href="mailto:{{ user.email }}">{{ user.email }}</a>
                                        <br>{{ user.phone_number }}
                                    </td>
                                    <td class="residence">
                                        City: {{ user.city }}
                                        <br>Zip: {{ user.zip }}
                                        <br>Country: {{ user.country }}
                                        <br>Address: {{ user.street }}
                                    </td>
                                    <td class="wallet">
                                        <strong>Wallet:</strong> &euro; {{ user.balance }}
                                        <br><strong>Winning:</strong> &euro; 0
                                    </td>
                                    <td>
                                        <form method="post" name="form{{ user.id }}" action="/admin/users/updateBalance">
                                            Amount € <input maxlength="12" type="numeric" name="amount" id="amount"/>
                                            Withdrawable? <input type="checkbox" name="withdrawable">
                                            <br><br>
                                            Reason <textarea maxlength="200" style="margin-left: 12px" type="text" name="reason" ></textarea>
                                            <a href="#" onclick="$(this).closest('form').submit()" class="btn btn-primary">Adjust Balance</a>
                                            <input type="hidden" name="user_id" value="{{ user.id }}"/>
                                        </form>
                                    </td>
                                    <td class="action">
                                        <a href="javascript:void(0)" class="btn btn-danger">Delete</a>
                                        <a href="#" class="btn btn-success">View Transactions</a>
                                        <a href="javascript:void(0)" data-id="{{ user.id }}" class="btn btn-primary">Edit</a>
                                    </td>
                                </tr>
                            {% endfor %}
                            {{ paginator_view_bets }}
                        {% endif %}
                    </tbody>
                </table>
           </div>
                <div class="hidden-element crud-user">
                    <h2 class="sub-title purple"></h2>
                    {% include "_elements/registration.volt" %}
                </div>

                 /* View Transactions */
                <h2 class="sub-title purple">View Transactions</h2>
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
                            <th class="type">Transaction</th>
                            <th class="movement">Movement</th>
                            <th class="wallet2">Wallet</th>
                            <th class="winnings">Winnings</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="date">16 May 2015, 18:35</td>
                            <td class="type">Played lotto</td>
                            <td class="movement">&euro; -15</td>
                            <td class="wallet2">&euro; 75</td>
                            <td class="winnings">&euro; 75</td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>

{% endblock %}
