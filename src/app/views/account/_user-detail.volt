<ul class="no-li">
    <li>
        <label class="label" for="name">{{ language.translate("Name") }} <span class="asterisk">*</span></label>
        <input id="name" class="input" type="text">
    </li>
    <li>
        <label class="label" for="surname">{{ language.translate("Surname") }} <span class="asterisk">*</span></label>
        <input id="surname" class="input" type="text">
    </li>
    <li>
        <label class="label" for="email">{{ language.translate("Email") }} <span class="asterisk">*</span></label>
        <input id="email" class="input" type="email">
    </li>
    <li>
        <label class="label" for="country">{{ language.translate("Country of residence") }} <span class="asterisk">*</span></label>
        <select id="country" class="select">
            <option>{{ language.translate("Select a country") }}</option>
            <option>lorem ipsum 1</option>
            <option>lorem ipsum 2</option>
            <option>lorem ipsum 3</option>
            <option>lorem ipsum 4</option>
        </select>
    </li>
</ul>