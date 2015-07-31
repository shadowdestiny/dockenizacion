{% extends "main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/css/account.css">
{% endblock %}
{% block bodyClass %}numbers{% endblock %}

{% block header %}{% include "_elements/header.volt" %}{% endblock %}
{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}

{% block body %}
<main id="content">
    <div class="wrapper">
        <div class="nav box-basic">
            <ul class="no-li">
                <li><a class="active" href="javascript:void(0)">My Account <i class="ico ico-arrow-right"></i></a></li>
                <li><a href="javascript:void(0)">My Games <i class="ico ico-arrow-right"></i></a></li>
                <li><a href="javascript:void(0)">My Transactions <i class="ico ico-arrow-right"></i></a></li>
                <li><a href="javascript:void(0)">Messages <i class="ico ico-arrow-right"></i></a></li>
            </ul>
        </div>
        <div class="box-basic content">
            <h1 class="h1 title">My account</h1>
            <h2 class="h3 yellow">User detail</h2>
            <div class="wrap">
                <form novalidate>
                    <div class="cols">
                        <div class="col6">
                            <label class="label" for="name">Name <span class="asterisk">*</span></label>
                            <input id="name" class="input" type="text">

                            <label class="label" for="surname">Surname <span class="asterisk">*</span></label>
                            <input id="surname" class="input" type="text">

                            <label class="label" for="email">Email <span class="asterisk">*</span></label>
                            <input id="email" class="input" type="email">

                            <label class="label" for="country">Country of residence <span class="asterisk">*</span></label>
                            <select id="country" class="select">
                                <option>Select a country</option>
                                <option>lorem ipsum 1</option>
                                <option>lorem ipsum 2</option>
                                <option>lorem ipsum 3</option>
                                <option>lorem ipsum 4</option>
                            </select>
                        </div>
                        <div class="col6">
                            <label class="label" for="street">Street address</label>
                            <input id="street" class="input" type="text">

                            <label class="label" for="po">ZIP / Postal code</label>
                            <input id="po" class="input" type="text">

                            <label class="label" for="city">City</label>
                            <input id="city" class="input" type="text">

                            <label class="label" for="phone">Phone Number</label>
                            <input id="phone" class="input" type="text">
                        </div>
                    </div>
                    <div class="cl"> 
                        <a href="javascript:void(0)" class="btn gwy big">Change password</a>
                        <label class="btn big blue right" for="submit">
                            Update profile details
                            <input id="submit" type="submit" class="hidden2"> 
                        </label>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
{% endblock %}