{% set jlength = jackpot_value |length %} {# Check length of the jackpot #}
{% if jlength <= 10 %}jl-10 {% endif %}{% if jlength == 11 %}jl-11 {% endif %}{% if jlength == 12 %}jl-12 {% endif %}{% if jlength == 13 %}jl-13 {% endif %}{% if jlength == 14 %}jl-14 {% endif %}{% if jlength == 15 %}jl-15 {% endif %}{% if jlength >= 16 %}jl-17 {% endif %}
