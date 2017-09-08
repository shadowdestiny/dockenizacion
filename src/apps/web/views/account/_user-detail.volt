<ul class="no-li">
    <li>
        <label class="label" for="name">{{ language.translate("account_name") }} <span class="asterisk">*</span></label>
        {{ myaccount.render('name', {'class':'input' }) }}
    </li>
    <li>
        <label class="label" for="surname">{{ language.translate("account_surname") }} <span class="asterisk">*</span></label>
        {{ myaccount.render('surname', {'class':'input' }) }}
    </li>
    <li>
        <label class="label" for="email">{{ language.translate("account_email") }}</label>
        {{ myaccount.render('email', {'class':'input','disabled':'disabled'}) }}
    </li>
    <li>
        <label class="label" for="country">{{ language.translate("account_country") }}</label>
        {{ myaccount.render('country', {'class':'select','disabled':'disabled'}) }}
    </li>
</ul>