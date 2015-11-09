{% extends "main.volt" %}

{% block bodyClass %}news{% endblock %}

{% block meta %}<title>News - Euromillions Admin System</title>{% endblock %}

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
                <h1 class="h1 purple">News</h1>
                *add overview all news (this is only detail)*

                <div class="stream-composer media">
                    <div class="media-body">
                        <div class="row-fluid">
                            <label for="input1">Title</label>
                            <input id="input1" class="inpu span8" type="text">

                            <label for="textarea1">Content</label>
                            <textarea id="textarea1" rows="8" class="textarea span12"></textarea>
                        </div>
                        <div class="clearfix">
                            <a class="btn btn-primary pull-right" href="#">
                                Post News
                            </a>
                            <a data-original-title="Upload a photo" data-placement="top" rel="tooltip" class="btn btn-small" href="#">
                                <i class="icon-camera"></i>
                            </a>
                            <a data-original-title="Upload a video" data-placement="top" rel="tooltip" class="btn btn-small" href="#">
                                <i class="icon-facetime-video"></i>
                            </a>
                            <a rel="tooltip" class="btn btn-small txt" href="#">
                                <strong>Bold</strong>
                            </a>
                            <a rel="tooltip" class="btn btn-small txt" href="#">
                                <em>Italic</em>
                            </a>
                            <a rel="tooltip" class="btn btn-small txt" href="#">
                                <i class="ico icon-link"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="stream-list">
                    <div class="media stream">
                        <div class="media-body">
                            <div class="stream-headline">
                                <h5 class="stream-author">
                                    My Title
                                    <small>08 July, 2014</small>
                                </h5>
                                <div class="stream-attachment photo">
                                    <div class="responsive-photo">
                                        <img src="/a/images/img.jpg">
                                    </div>
                                </div>

                                <div class="stream-text">
                                     Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type. Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type. 
                                </div>
                            </div><!--/.stream-headline-->

                        </div>
                    </div><!--/.media .stream-->

                </div><!--/.stream-list-->
            </div><!--/.module-body-->
        </div>
    </div>
</div>
{% endblock %}