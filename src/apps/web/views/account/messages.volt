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
{% block template_scripts %}<script src="/w/js/mobileFix.min.js"></script>{% endblock %}

{% block body %}
<main id="content" class="account-page">
    <div class="wrapper">
        {% include "account/_breadcrumbs.volt" %}
        <div class="nav">
           {% set activeSubnav='{"myClass": "messages"}'|json_decode %}
           {% include "account/_nav.volt" %}
        </div>
        <div class="content">
            <div class="hidden right back">
                <a class="btn" href="javascript:void(0);">{{ language.translate("balance_back_btn") }}</a>
            </div>
            <h1 class="h1 title yellow">Messages</h1>


    * NO MESSAGE <br> *
                <div class="info box">
                    <svg class="ico v-info"><use xlink:href="/w/svg/icon.svg#v-info"></use></svg>
                    <span class="txt">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod.</span>
                </div>

    * WITH MESSAGE <br> *

            <!--start PROD imports
            <script src="/w/js/dist/btnMsgHide.min.js"></script>
            end PROD imports-->
            <!--start DEV imports-->
            <script src="/w/js/btnMsgHide.js"></script>
            <!--end DEV imports-->

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
                            <td class="mail"><svg class="ico v-email"><use xlink:href="/w/svg/icon.svg#v-email"></use></svg></td>
                            <td class="date">16 May 2015</td>
                            <td class="title">Lorem ipsum dolor sit amet</td>
                            <td class="from">Aenan commodo ligula eget &hellip;</td>
                        </tr>
                        <tr class="msg-2">
                            <td class="mail"><svg class="ico v-email-open"><use xlink:href="/w/svg/icon.svg#v-email-open"></use></svg></td>
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