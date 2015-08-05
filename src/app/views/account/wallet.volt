{% extends "main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/css/account.css">
{% endblock %}
{% block bodyClass %}wallet{% endblock %}

{% block template_scripts %}

{% endblock %}

{% block header %}
    {% set activeNav='{"myClass": "account"}'|json_decode %}
    {% include "_elements/header.volt" %}
{% endblock %}
{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}

{% block body %}

<main id="content">
    <div class="wrapper">
        <div class="nav box-basic">
           {% set activeSubnav='{"myClass": "wallet"}'|json_decode %}
           {% include "account/nav.volt" %}
        </div>
        <div class="box-basic content">
            <h1 class="h1 title yellow">{{ language.translate("My Wallet") }}</h1>
            <h2 class="h3 yellow">{{ language.translate("Add funds to your wallet") }}</h2>

            <div class="info box">
                <i class="ico ico-info"></i>
                <span class="txt">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.</span>
                <a href="javascript:void(0)" class="btn gwy">Add a new Credit Card</a>
            </div>

            <div class="cl box-wallet">
                <div class="value">
                    <span class="purple">Wallet balance:</span> 20 &euro;
                </div>

                <div class="right">
                    <span class="symbol">&euro;</span>
                    <input class="input" type="text" placeholder="Enter any amount">
                    <a href="javascript:void(0)" class="btn green">Add funds to your wallet</a>
                </div>
            </div>

            <hr class="yellow">

            <div class="info box">
                <i class="ico ico-info"></i>
                <span class="txt"><span class="congrats">Congratulations!!! You won 100.000 &euro;</span> Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.</span>
                <a href="javascript:void(0)" class="btn blue">Add a new Credit Card</a>
            </div>

            <div class="info box">
                <i class="ico ico-info"></i>
                <span class="txt">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.</span>
                <a href="javascript:void(0)" class="btn gwy">Add a new Bank Account</a>                
            </div>

            <div class="cl box-wallet">
                <div class="value">
                    <span class="purple">Wallet balance:</span> 500 &euro;
                </div>

                <div class="right">
                    <span class="symbol">&euro;</span>
                    <input class="input" type="text" placeholder="Enter any amount">
                    <a href="javascript:void(0)" class="btn blue">Withdraw winnings</a>
                </div>
            </div>


        </div>
    </div>
</main>
{% endblock %}