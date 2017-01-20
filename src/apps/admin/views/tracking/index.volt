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

    <div class="wrapper">
        <div class="container">
            <div class="module">
                <div class="module-body">
                    <h1 class="h1 purple">Tracking Codes</h1>
                    <p><b class="purple">Create new Tracking Code</b></p>
                    <form>
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
                    <br />
                    <p><b class="purple">Tracking Codes</b></p>
                    <table class="table" width="100%">
                        <thead>
                        <tr class="special">
                            <th width="20%">Name</th>
                            <th width="45%">Description</th>
                            <th width="10%">Users</th>
                            <th width="25%">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                Inactives_12_2016
                            </td>
                            <td>
                                Inactive players December 2016. Only lottery 1
                            </td>
                            <td>
                                1234
                            </td>
                            <td>
                                <input type="button" class="btn btn-primary" value="Edit" />
                                <input type="button" class="btn btn-primary" value="Delete" />
                                <input type="button" class="btn btn-primary" value="Download" />
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

{% endblock %}