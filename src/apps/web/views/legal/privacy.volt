{% extends "main.volt" %}
{% block template_css %}<link rel="stylesheet" href="/w/css/legal.css">{% endblock %}
{% block bodyClass %}privacy{% endblock %}

{% block header %}
    {% set activeNav='{"myClass": ""}'|json_decode %}
    {% include "_elements/header.volt" %}
{% endblock %}
{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}
{% block template_scripts %}<script src="/w/js/mobileFix.js"></script>{% endblock %}

{% block body %}
<main id="content">
    <div class="wrapper">
        <div class="nav box-basic">
           {% set activeSubnav='{"myClass":"privacy"}'|json_decode %}
           {% include "legal/_nav.volt" %}
        </div>
        <div class="box-basic content">
            <h1 class="h1 title">{{ language.translate("Privacy Policy") }}</h1>
            <h2 class="h3">{{ language.translate("Information Collection and Use") }}</h2>
            <p>{{ language.translate("Euromillions.com is the sole owner of the information collected at various points on this website. We will not sell, share, or rent this information to third parties in ways that are not disclosed in this statement.") }}</p>

            <h2 class="h3">{{ language.translate("Registration") }}</h2>
            <p>{{ language.translate("In order to initiate a purchase assignment on this website, users have to register first. During registration a user is required to provide his/her contact information (such as email address and password). This information is used to contact, bill and identify the user in regard to services on our site.") }}</p>

            <h2 class="h3">{{ language.translate("Order") }}</h2>
            <p>{{ language.translate("In order to participate in the games on our site, users have to provide financial information solely on the surfaces of our payment providers. Euromillions.com does not see, save or track credit card information of the customer.") }}</p>
            
            <h2 class="h3">{{ language.translate("Log Files") }}</h2>
            <p>{{ language.translate("From time to time we use IP addresses to analyze trends, administer the site, track users' movement, and gather broad demographic information for statistical purposes.") }}</p>

            <h2 class="h3">{{ language.translate("Links") }}</h2>
            <p>{{ language.translate("This web site contains links to other sites. Please be aware that Euromillions.com is not responsible for the content and privacy practices of such other sites. This privacy statement applies solely to information collected by the website of Euromillions.com") }}</p>

            <h2 class="h3">{{ language.translate("Cookies") }}</h2>
            <p>{{ language.translate("Our website utilizes cookies to keep certain settings or preferences of the website so when the user returns, the settings make sure the website acts or looks the same.") }}</p> 
            
            <h2 class="h3">{{ language.translate("Security") }}</h2>
            <p>{{ language.translate("This website takes every precaution to protect users' information. When users submit sensitive information on the website, their information is protected both online and off-line. When the site requests sensitive information, that information is encrypted and protected with SSL 128 bit.Access to user information is only granted to staff members who need the information to perform a specific job.") }}</p>

            <h2 class="h3">{{ language.translate("Site and Service Updates") }}</h2>
            <p>{{ language.translate("Every now and than, we e-mail website and service announcements to our registered users.") }}</p>

            <h2 class="h3">{{ language.translate("Notification system") }}</h2>
            <p>{{ language.translate("We supply an e-mail based notification system to our users. Each notification service can be activated or discontinued at any time in the user's player account.") }}</p>

            <h2 class="h3">{{ language.translate("Correction/Updating Personal Information</h2>
            <p>Our registered customers can access and update their provided information in their player account. If a user wishes to completely suspend and remove the account, our customer service will help.") }}</p>

            <h2 class="h3">{{ language.translate("Notification of Changes") }}</h2>
            <p>{{ language.translate("If we decide to change our privacy policy, we will post those changes on this webpage so our users are always aware of what information we collect, how we use it, and under what circumstances, if any, we disclose it. If at any point we decide to use personally identifiable information in a different manner than stated at the time it was collected, we will notify our customers per email. Users will have a choice as to whether or not we use their information in this different manner. We will use information in accordance with the privacy policy under which the information was collected. During the purchase process, before the payment, customers always have to accept the most up to date Terms and Conditions.") }}</p>

            <h2 class="h3">{{ language.translate("Legal Disclaimer") }}</h2>
            <p>{{ language.translate("Though we make every effort to preserve your privacy, we may need to disclose personal information when required by law wherein we have a good-faith belief that such action is necessary to comply with a current judicial proceeding, a court order or legal process demanded by our 
            website.") }}</p>
            <hr class="yellow">
            <br><p>{{ language.translate("If you have any additional questions or suggestions regarding our privacy policy, please contact us at: <a href='mailto:support@euromillions.com'>support@euromillions.com</a>") }}</p>
        </div>
    </div>
</main>
{% endblock %}