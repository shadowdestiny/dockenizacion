<header data-role="header">
    {% include "_elements/top-bar.volt" %}

    <div class="head">
        <div class="wrapper">
            {% include "_elements/logo.volt" %}

            <div class="steps">
                <div class="line basic"></div>
                <div class="line position 
                    {% if activeSteps.myClass == 'step1' %}
                        step1
                    {% elseif activeSteps.myClass == 'step2' %}
                        step2
                    {% elseif activeSteps.myClass == 'step3' %}
                        step3
                    {% endif %}">
                    <svg class="ico v-cart"><use xlink:href="/w/icon.svg#v-cart"></use></svg>
                </div>
                <ol class="no-li names cl">
                    <li class="step1 {% if activeSteps.myClass == 'step1' %}active{% endif %}">{{ language.translate("Sign in") }}</li>
                    <li class="step2 {% if activeSteps.myClass == 'step2' %}active{% endif %}">{{ language.translate("Your details") }}</li>
                    <li class="step3 {% if activeSteps.myClass == 'step3' %}active{% endif %}">{{ language.translate("Place order") }}</li>
                </ol>
            </div>

        </div>
    </div>
</header>