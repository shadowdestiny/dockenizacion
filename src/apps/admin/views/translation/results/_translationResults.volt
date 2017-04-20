{% if keysList is not empty %}
    <table class="table">
        <thead>
        <tr class="special">
            <th>Translation Key</th>
            <th>Description</th>
        </tr>
        </thead>
        <tbody>
        {% for key in keysList %}
            <tr>
                <td>
                    {{ key.getKey() }}
                </td>
                <td>
                    {{ key.getDescription() }}
                </td>
            </tr>
        {% endfor %}
        </tbody>
    </table>
{% else %}
    Not translation keys for this search.
{% endif %}