{% if keysList is not empty %}
    <form action="/admin/translation/saveTranslations" method="POST">
    <table class="table">
        <thead>
        <tr class="special">
            <th>Translation Key</th>
            <th>Description</th>
            {% for language in languages %}
                <th>{{ language.getDefaultLocale() }}</th>
            {% endfor %}
        </tr>
        </thead>
        <tbody>
        {% for key in keysList %}
            <tr>
                <td>
                    {{ key['translationKey'] }}
                </td>
                <td>
                    {{ key['description'] }}
                </td>
                {% for language in languages %}
                    <td><textarea name="{{ key['id'] }}|{{ language.getId() }}|{{ language.getCcode() }}|{{ key['translationKey'] }}" id="{{ key['id'] }}|{{ language.getId() }}|{{ language.getCcode() }}|{{ key['translationKey'] }}">{% if key[language.getCcode()] is defined %}{{ key[language.getCcode()] }}{% endif %}</textarea></td>
                {% endfor %}
            </tr>
        {% endfor %}
        </tbody>
    </table>
    <br />
    <p align="center"><input type="submit" value="Save Translations" class="btn btn-primary" /></p>
    </form>
{% else %}
    Not translation keys for this search.
{% endif %}