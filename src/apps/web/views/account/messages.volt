{% extends "main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/w/css/account.css">
{% endblock %}
{% block bodyClass %}messages{% endblock %}

{% block header %}
    {% set activeNav='{"myClass": "account"}'|json_decode %}
    {% include "_elements/header.volt" %}
{% endblock %}
{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}

{% block body %}

<main id="content">
    <div class="wrapper">
        <div class="nav box-basic">
           {% set activeSubnav='{"myClass": "messages"}'|json_decode %}
           {% include "account/_nav.volt" %}
        </div>
        <div class="box-basic content">
            <div class="hidden right back">
                <a class="btn" href="javascript:void(0);">Go Back</a>
            </div>
            <h1 class="h1 title yellow">Messages</h1>


    * NO MESSAGE <br> *
                <div class="info box">
                    <i class="ico ico-info"></i>
                    <span class="txt">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod.</span>
                </div>

    * WITH MESSAGE <br> *

            {# EMTD 
                1) IF THERE IS A MESSAGE USE THIS SCRIPT OTHERWISE HIDE THE SCRIPT 
                2) Decide if inject as ajax the message or print all the messages visible and show/hide with JS
            #}
            <script>
            $(function(){
                 btnShowHide('.msg-1', '.single-msg, .back', '.box-messages');
                 btnShowHide('.back', '.box-messages', '.single-msg, .back');
            });
            </script>

            <div class="box-messages">
                <table class="table ui-responsive" data-role="table" data-mode="reflow">
                    <thead>
                        <tr>
                            <th class="mail">&nbsp;</th>
                            <th class="date">Date</th>
                            <th class="title">Title</th>
                            <th class="from">Content</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="unread msg-1">
                            <td class="mail"><i class="ico ico-email"></i></td>
                            <td class="date">16 May 2015</td>
                            <td class="title">Lorem ipsum dolor sit amet</td>
                            <td class="from">Aenan commodo ligula eget &hellip;</td>
                        </tr>
                        <tr class="msg-2">
                            <td class="mail"><i class="ico ico-email-open"></i></td>
                            <td class="date">16 May 2015</td>
                            <td class="title">Lorem ipsum dolor sit amet</td>
                            <td class="from">Aenan commodo ligula eget &hellip;</td>
                        </tr>
                    </tbody>
                </table>

                {% include "account/_paging.volt" %}
            </div>

            <div class="hidden single-msg">
                <h2 class="purple h3">Lorem ipsum dolor sit amet</h2>
                <p>Ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod.Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod.</p>
                <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod.</p>
            </div>

        </div>
    </div>
</main>
{% endblock %}