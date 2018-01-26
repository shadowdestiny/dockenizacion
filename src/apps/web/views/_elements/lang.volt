<li class="li-language" id="li-language--desktop">
    <a class="link myLang li-language--main-link" href="javascript:void(0);">{{ language.translate(user_language) }}</a>
    <div class="div-language">
        <div class="div-language--shade"></div>
        <div class="div-language--content">
        <ul class="no-li">
            <li class="language--li--current">
                <a class="link myLang li-language--main-link" href="javascript:void(0);">{{ language.translate(user_language) }}</a>
            </li>
            {% for active_language in active_languages %}
                {% if active_language != user_language %}
                    <li>
                        <a href="javascript:globalFunctions.setLanguage('{{ active_language }}');">{{ language.translate(active_language) }}</a>
                    </li>
                {% endif %}
            {% endfor %}
        </ul>
    </div>
    </div>
</li>