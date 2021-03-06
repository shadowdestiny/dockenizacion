<script>var myLogged = '<?php echo $user_logged; ?>'</script> {# This value is used in mobileFix.js #}
{#<div class="cookies-block--mobile mobile">#}
    {#{% include "../../shared/views/_elements/cookies.volt" %}#}
    {#{% include "../../shared/views/_elements/cookies--desktop.volt" %}#}
{#</div>#}
<div class="cookies-block--desktop">
    {#{% include "../../shared/views/_elements/cookies--desktop.volt" %}#}
    {% include "../../shared/views/_elements/cookies.volt" %}
</div>
<header data-role="header" class="header">
    <div class="top-bar--desktop">
        {% include "../../shared/views/_elements/top-bar--desktop.volt" %}
    </div>
    <div class="top-bar--mobile">
        {% include "../../shared/views/_elements/top-bar--mobile.volt" %}
    </div>

    <div class="head">
        <div class="wrapper wrapper--desktop-menu">
            {#{% include "../../shared/views/_elements/logo.volt" %}#}
            <nav class="main-nav desktop">
                <ul>
                    {% include "../../shared/views/_elements/nav--desktop.volt" %}
                </ul>
            </nav>
        </div>
    </div>
    <nav class="nav mobile">
        <div class="nav-mobile--inner wrapper">
            <div class="menu-account-block">
                {% include "../../shared/views/_elements/top-nav--mobile-account.volt" %}
            </div>

            <button class="menu-ham"><span class="bar"></span></button>

            <ul>
                <div class="wrapper">
                {#{% include "../../shared/views/_elements/top-nav--mobile.volt" %}#}
                {#{% include "../../shared/views/_elements/nav--mobile.volt" %}#}
                </div>
            </ul>
        </div>

    </nav>
</header>