{% extends "main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/css/account.css">
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
           {% include "account/nav.volt" %}
        </div>
        <div class="box-basic content">

            <h1 class="h1 title yellow">Messages</h1>

* NO MESSAGE <br> *
            <div class="info box">
                <i class="ico ico-info"></i>
                <span class="txt">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod.</span>
            </div>


* WITH MESSAGE <br> *
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
                    <tr class="unread">
                        <td class="mail"><i class="ico ico-email"></i></td>
                        <td class="date">16 May 2015</td>
                        <td class="title">Lorem ipsum dolor sit amet</td>
                        <td class="from">Aenan commodo ligula eget &hellip;</td>
                    </tr>
                    <tr>
                        <td class="mail"><i class="ico ico-email-open"></i></td>
                        <td class="date">16 May 2015</td>
                        <td class="title">Lorem ipsum dolor sit amet</td>
                        <td class="from">Aenan commodo ligula eget &hellip;</td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>
</main>
{% endblock %}