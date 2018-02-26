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
    function editPost(id)
    {
        location='/admin/blog/editPost?id='+id;
    }
</script>
    <div class="wrapper">
        <div class="container">
            <div class="module">
                <div class="module-body">
                    <h1 class="h1 purple">Blog</h1>
                    <p><b class="purple">Posts</b></p>
                    {% if postsList is not empty %}
                        <table class="table" width="100%">
                            <thead>
                            <tr class="special">
                                <th>Title</th>
                                <th>URL</th>
                                <th>Language</th>
                                <th>Published</th>
                                <th> </th>
                            </tr>
                            </thead>
                            <tbody>
                            {% for post in postsList %}
                                <tr>
                                    <td>
                                        {{ post.getTitle() }}
                                    </td>
                                    <td>
                                        {{ post.getUrl() }}
                                    </td>
                                    <td>
                                        {{ post.getLanguage() }}
                                    </td>
                                    <td>
                                        {% if post.getPublished() == 1 %}
                                            <span style="color: green;">Yes</span>
                                        {% else %}
                                            <span style="color: red;">No</span>
                                        {% endif %}
                                    </td>
                                    <td>
                                        <input type="button" class="btn btn-primary" value="Edit"  onClick="editPost({{ post.getId() }});" />
                                    </td>
                                </tr>
                            {% endfor %}
                            </tbody>
                        </table>
                    {% else %}
                        We don't have posts yet, create one.
                    {% endif %}
                </div>
            </div>
        </div>
    </div>

{% endblock %}