<h3>test view</h3>
variable 1 (string) <strong>{{ variable1 }}</strong><br>
variable 2 (number) <strong>{{ variable2 }}</strong><br>
variable 3 (array of one string and one integer)
<strong>
    {% for product in variable3 %}
{{ product }},
{% endfor %}
</strong>
<br>
variable 4 (array)
<strong>{{ variable4['elem1'] }} - {{ variable4['elem2'] }}</strong>
<br>
variable 4 (array showing keys)
<strong>
    {% for elemkey, value in variable4 %}
        ['{{ elemkey }}']:{{ value }},
    {% endfor %}
</strong>
<br>
<br>
variable 5 (object)
<strong>{{ variable5.property1 }} - {{ variable5.property2 }}</strong>

