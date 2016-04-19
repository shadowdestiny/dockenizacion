<ul class="no-li">
    <li>
        <label class="label" for="name">{{ language.translate("Name") }} <span class="asterisk">*</span></label>
        {{ myaccount.render('name', {'class':'input' }) }}
    </li>
    <li>
        <label class="label" for="surname">{{ language.translate("Surname") }} <span class="asterisk">*</span></label>
        {{ myaccount.render('surname', {'class':'input' }) }}
    </li>
    <li>
        <label class="label" for="email">{{ language.translate("Email") }}</label>
        {{ myaccount.render('email', {'class':'input','disabled':'disabled'}) }}
    </li>
    <li>
        <label class="label" for="country">{{ language.translate("Country of residence") }}</label>
        {{ myaccount.render('country', {'class':'select','disabled':'disabled'}) }}
    </li>
</ul>