{% extends "main.volt" %}

{% block template_css %}
    <link rel="stylesheet" href="/a/css/pagination.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.25.0/codemirror.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.7.5/css/froala_editor.pkgd.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.7.5/css/froala_style.min.css" rel="stylesheet" type="text/css" />
{% endblock %}

{% block bodyClass %}jackpot{% endblock %}

{% block template_scripts %}
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.25.0/codemirror.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.25.0/mode/xml/xml.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.7.5/js/froala_editor.pkgd.min.js"></script>
    <script> $(function() { $('textarea').froalaEditor() }); </script>
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
                <h1 class="h1 purple">Create new Post</h1>
                {#<p><b class="purple">Create new Post</b></p>#}
                {% if errorMessage is not empty %}
                    <p>{{ errorMessage }}</p>
                {% endif %}
                {%  if post is defined %}
                    <form method="post" action="/admin/blog/updatePost">
                {% else %}
                    <form method="post" action="/admin/blog/createPost">
                {% endif %}
                    <table>
                        <thead>
                        <tr>
                            <td>Title Tag</td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <input type="text" name="title_tag" value="{%  if post is defined %}{{ post.getTitleTag() }}{% endif %}"/>
                            </td>
                        </tr>
                        </tbody>
                        <thead>
                        <tr>
                            <td>Meta Description</td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>

                            <td>
                                <input type="text" name="description_tag" value="{%  if post is defined %}{{ post.getDescriptionTag() }}{% endif %}"/>
                            </td>
                        </tr>
                        </tbody>
                        <thead>
                        <tr>
                            <td>Url</td>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <input type="text" name="url" value="{%  if post is defined %}{{ post.getUrl() }}{% endif %}"/>
                                </td>
                            </tr>
                        </tbody>
                        <thead>
                        <tr>
                            <td>Title</td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <input type="text" name="title" value="{%  if post is defined %}{{ post.getTitle() }}{% endif %}"/>
                            </td>
                        </tr>
                        </tbody>
                        <thead>
                        <tr>
                            <td>Description</td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>

                            <td>
                                <input type="text" name="description" value="{%  if post is defined %}{{ post.getDescription() }}{% endif %}"/>
                            </td>
                        </tr>
                        </tbody>
                        <thead>
                        <tr>
                            <td>Canonical</td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <input type="text" name="canonical" value="{%  if post is defined %}{{ post.getCanonical() }}{% endif %}"/>
                            </td>
                        </tr>
                        </tbody>
                        <thead>
                        <tr>
                            <td>Language</td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <input type="text" name="language" value="{%  if post is defined %}{{ post.getLanguage() }}{% endif %}"/>
                            </td>
                        </tr>
                        </tbody>
                        <thead>
                        <tr>
                            <td>Published</td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <input type="checkbox" name="published" {%  if post is defined %}{% if post.getPublished() == '1' %}checked{% endif %}{% endif %}/>
                            </td>
                        </tr>
                        </tbody>
                        <thead>
                        <tr>
                            <td>Content</td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <textarea name="content" >{%  if post is defined %}{{ post.getContent() }}{% endif %}</textarea>
                            </td>
                        </tr>
                        </tbody>
                        <thead>
                        <tr>
                            <td></td>
                        </tr>
                        </thead>
                        <tr>
                            <td>
                            {%  if post is defined %}
                                <input type="hidden" value="{{ post.getId() }}" name="id"/>
                                <input type="submit" class="btn btn-primary" value="Update Post" />
                            {% else %}
                                <input type="submit" class="btn btn-primary" value="Create Post" />
                            {% endif %}
                            </td>
                        </tr>
                    </table>
                </form>

            </div>
        </div>
    </div>
</div>
{% endblock %}