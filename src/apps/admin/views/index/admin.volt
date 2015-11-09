{% extends "main.volt" %}

{% block bodyClass %}admin{% endblock %}

{% block meta %}<title>Admin area - Euromillions Admin System</title>{% endblock %}

{% block header %}
{% set activeNav='{"myClass": ""}'|json_decode %} {# It need to be empty #}
{% include "_elements/header.volt" %}
{% endblock %}

{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}

{% block body %}
 <div class="wrapper">
    <div class="container">

        <div class="content">
            <h1 class="h1 purple">Admin Area</h1>

            <div class="alert alert-success">
                <button data-dismiss="alert" class="close" type="button">×</button>
                <strong>*Email User* is successfully being added</strong>  as *user type* and an email is being automatically sent.
            </div>
            <div class="alert alert-success">
                <button data-dismiss="alert" class="close" type="button">×</button>
                <strong>*Email User* is being modified</strong> as *user type*.
            </div>
            <div class="alert alert-success">
                <button data-dismiss="alert" class="close" type="button">×</button>
                <strong>*Email User* is being deleted</strong>
            </div>

            <table class="table">
                <thead>
                    <tr class="special">
                        <th>Email</th>
                        <th class="type">User Type</th>
                        <th class="action">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><a href="mailto:otto@panamedia.net">otto@panamedia.net</a></td>
                        <td class="type">Super Admin</td>
                        <td class="action">
                            <a href="#" class="btn btn-danger">Delete</a>
                            <a href="#" class="btn btn-primary">Edit</a> 
                        </td>
                    </tr>
                    <tr>
                       <td><a href="mailto:otto@panamedia.net">mark@panamedia.net</a></td>
                       <td class="type">Tech</td>
                        <td class="action">
                            <a href="#" class="btn btn-danger">Delete</a>
                            <a href="#" class="btn btn-primary">Edit</a> 
                        </td>                            </tr>
                    <tr>
                        <td><a href="mailto:otto@panamedia.net">mario@panamedia.net</a></td>
                        <td class="type">Business, News</td>
                        <td class="action">
                            <a href="#" class="btn btn-danger">Delete</a>
                            <a href="#" class="btn btn-primary">Edit</a> 
                        </td>
                    </tr>
                    <tr>
                        <td><a href="mailto:otto@panamedia.net">robert.ciao@gmail.com</a></td>
                        <td class="type">News</td>
                        <td class="action">
                            <a href="#" class="btn btn-danger">Delete</a>
                            <a href="#" class="btn btn-primary">Edit</a> 
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="cl">
                <a href="#" class="btn btn-primary right">Add New User</a>
            </div>
        </div>
        <div class="module">
            <div class="module-head cl">
                <h3 class="title">Add New User</h3>
            </div>
            <div class="module-body">
                <div class="row-fluid">
                    <div class="span6">
                        <label>Email</label>
                        <input class="span8" type="text">
                    </div>        
                    <div class="span6">
                        <label>Temporary Password</label>
                        <input class="span8" type="text" value="Adfd2a5!">
                        <br>/*It should be automatic generated and sent by email as well; It should not be showed when Edi an user information */
                    </div>                        
                </div>
                <h4>User Type</h4>
                <p>An user can have more than one type of user type associated to the same account (<em>Exception: Super Admin and Basic User</em>)</p>

                <div class="legend">
                    <h3>Legend</h3>
                    <div class="row-fluid">
                        <div class="span6">
                            <p><strong>Super Admin:</strong> Ability to see and edit everything, and add users.</p>
                        </div>
                        <div class="span6">
                            <p><strong>Admin:</strong> Ability to see and edit everything</p>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span6">
                            <p><strong>Tech:</strong> Ability to see everything.</p>
                        </div>
                        <div class="span6">
                            <p><strong>Support:</strong> Ability to see and edit panel of support and statistic related to support.</p>
                        </div>
                    </div>
                    <div class="row-fluid">

                        <div class="span6">
                            <p><strong>Business:</strong> Ability to see and edit only the Business Statistics.</p>
                        </div>
                        <div class="span6">
                            <p><strong>News:</strong> Ability to see and edit only the News.</p>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span6">
                            <p><strong>Translator:</strong> Ability to see and edit only the Language related to their expertise.</p>
                        </div>
                    </div>
                </div>

                <div class="row-fluid" style="margin-bottom:20px;">
                    <div class="span4">
                        <label class="checkbox">
                            <input type="checkbox" checked="checked" value="">
                            Super Admin
                        </label>
                        <label class="checkbox">
                            <input type="checkbox" value="">
                            Tech
                        </label>
                        <label class="checkbox">
                            <input type="checkbox" value="">
                            Support
                        </label>
                    </div>
                    <div class="span4">
                        <label class="checkbox">
                            <input type="checkbox" value="">
                            Admin
                        </label>
                        <label class="checkbox">
                            <input type="checkbox" value="">
                            News
                        </label>
                    </div>
                    <div class="span4">
                        <label class="checkbox">
                            <input type="checkbox" value="">
                            Translator
                            <select class="select">
                                <option>DE</option>
                                <option>EN</option>
                                <option>ES</option>
                                <option>FR</option>
                                <option>IT</option>
                            </select>
                        </label>
                        <label class="checkbox">
                            <input type="checkbox" value="">
                            Business
                        </label>
                    </div>
                </div>
                <div class="cl">
                    <a href="#" class="btn btn-inverse left">Cancel</a>
                    <a href="#" class="btn btn-primary right">Add New User</a>
                </div>
            </div>
        </div>
    </div>
</div>
{% endblock %}