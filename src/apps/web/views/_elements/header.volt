<script>var myLogged = '<?php echo $user_logged; ?>'</script> {# This value is used in mobileFix.js #}
{#<div class="cookies-block--mobile mobile">#}
    {#{% include "_elements/cookies.volt" %}#}
    {#{% include "_elements/cookies--desktop.volt" %}#}
{#</div>#}
<div class="cookies-block--desktop">
    {% include "_elements/cookies--desktop.volt" %}
</div>
<header data-role="header" class="header">
    <div class="top-bar--desktop">
        {% include "_elements/top-bar--desktop.volt" %}
    </div>
    <div class="top-bar--mobile">
        {% include "_elements/top-bar--mobile.volt" %}
    </div>

    <div class="head">
        <div class="wrapper">
            {#{% include "_elements/logo.volt" %}#}
            <nav class="main-nav desktop">
                <ul>
                    {% include "_elements/nav--desktop.volt" %}
                </ul>
            </nav>
        </div>
    </div>
    <nav class="nav mobile">
        <div class="nav-mobile--inner wrapper">
        <button class="menu-ham"><span class="bar"></span></button>

            <ul>
                <div class="wrapper">
                {#{% include "_elements/top-nav--mobile.volt" %}#}
                {% include "_elements/nav--mobile.volt" %}
                </div>
            </ul>
        </div>
    </nav>
</header>