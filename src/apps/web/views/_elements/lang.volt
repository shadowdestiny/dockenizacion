<li class="li-language">
    <a class="link myLang" href="javascript:void(0);">{{ language.translate(user_language) }}
        <svg class="ico v-triangle-down">
            <use xlink:href="/w/svg/icon.svg#v-triangle-down"></use>
        </svg>
    </a>
    <div class="div-language">
        <ul class="no-li">
            {% for active_language in active_languages %}
                {% if active_language != user_language %}
                    <li>
                        <a href="javascript:globalFunctions.setLanguage('{{ active_language }}');">{{ language.translate(active_language) }}</a>
                    </li>
                {% endif %}
            {% endfor %}
        </ul>
    </div>
</li>