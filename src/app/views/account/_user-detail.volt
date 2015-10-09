<ul class="no-li">
    <li>
        <label class="label" for="name">{{ language.translate("Name") }} <span class="asterisk">*</span></label>
        {{ myaccount.render('name', {'class':'input'~form_errors['name'],'value' : user_dto.name }) }}
    </li>
    <li>
        <label class="label" for="surname">{{ language.translate("Surname") }} <span class="asterisk">*</span></label>
        {{ myaccount.render('surname', {'class':'input'~form_errors['surname'], 'value' : user_dto.surname}) }}
    </li>
    <li>
        <label class="label" for="email">{{ language.translate("Email") }} <span class="asterisk">*</span></label>
        {{ myaccount.render('email', {'class':'input'~form_errors['email'], 'value' : user_dto.email}) }}
    </li>
    <li>
        <label class="label" for="country">{{ language.translate("Country of residence") }} <span class="asterisk">*</span></label>
        {{ myaccount.render('country', {'class':'select'~form_errors['country'], 'value' : user_dto.country}) }}
    </li>
</ul>