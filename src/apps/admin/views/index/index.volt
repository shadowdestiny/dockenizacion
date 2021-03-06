{% extends "main.volt" %}

{% block template_scripts %}
    <script src="/a/js/angular-admin/node_modules/es6-shim/es6-shim.min.js"></script>
    <script src="/a/js/angular-admin/node_modules/systemjs/dist/system-polyfills.js"></script>
    <script src="/a/js/angular-admin/node_modules/angular2/es6/dev/src/testing/shims_for_IE.js"></script>
    <script src="/a/js/angular-admin/node_modules/angular2/bundles/angular2-polyfills.js"></script>
    <script src="/a/js/angular-admin/node_modules/systemjs/dist/system.src.js"></script>
    <script>
        System.config({
            baseURL: '/a/js/angular-admin/',
            packages: {
                app : {
                    format: 'register',
                    defaultExtension: 'js'
                }
            }
        });
    </script>

    <script>
        System.import('app/main').then(function(m){
        });
    </script>
    <script src="/a/js/angular-admin/node_modules/rxjs/bundles/Rx.js"></script>
    <script src="/a/js/angular-admin/node_modules/angular2/bundles/angular2.dev.js"></script>
    <script src="/a/js/angular-admin/node_modules/angular2/bundles/router.dev.js"></script>
    <base href="/">
{% endblock %}
{% block bodyClass %}overview{% endblock %}

{% block meta %}<title>Overview - Euromillions Admin System</title>{% endblock %}

{% block header %}
{% set activeNav='{"myClass": ""}'|json_decode %} {# It need to be empty #}
{% include "_elements/header.volt" %}
{% endblock %}

{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}

{% block body %}
<div class="wrapper">
    <div class="container">
        <div class="row">
            <div class="span12">
                <div class="content">
                    <h1 class="h1 purple">Overview</h1>
                    <my-app>Loading...</my-app>
                </div>

            </div>
        </div>
    </div>
</div>
{% endblock %}