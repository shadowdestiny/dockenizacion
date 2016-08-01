System.register(['angular2/core', "angular2/router", "./components/list-language.component", "./components/edit-language.component", "./components/add-language.component", "./services/auth.service", "./components/list-translation.component"], function(exports_1, context_1) {
    "use strict";
    var __moduleName = context_1 && context_1.id;
    var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
        var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
        if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
        else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
        return c > 3 && r && Object.defineProperty(target, key, r), r;
    };
    var __metadata = (this && this.__metadata) || function (k, v) {
        if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
    };
    var core_1, router_1, list_language_component_1, edit_language_component_1, add_language_component_1, auth_service_1, list_translation_component_1;
    var AppComponent;
    return {
        setters:[
            function (core_1_1) {
                core_1 = core_1_1;
            },
            function (router_1_1) {
                router_1 = router_1_1;
            },
            function (list_language_component_1_1) {
                list_language_component_1 = list_language_component_1_1;
            },
            function (edit_language_component_1_1) {
                edit_language_component_1 = edit_language_component_1_1;
            },
            function (add_language_component_1_1) {
                add_language_component_1 = add_language_component_1_1;
            },
            function (auth_service_1_1) {
                auth_service_1 = auth_service_1_1;
            },
            function (list_translation_component_1_1) {
                list_translation_component_1 = list_translation_component_1_1;
            }],
        execute: function() {
            // Decorador component, indicamos en que etiqueta se va a cargar la plantilla
            AppComponent = (function () {
                function AppComponent(_authService) {
                    this._authService = _authService;
                }
                AppComponent.prototype.ngOnInit = function () {
                    this.getAuth();
                };
                AppComponent.prototype.getAuth = function () {
                    this._authService.login()
                        .subscribe(function (result) {
                    }, function (error) {
                    });
                };
                AppComponent = __decorate([
                    core_1.Component({
                        selector: 'translate-mg',
                        templateUrl: '/a/js/angular-admin/app/views/translates.html',
                        styleUrls: ["a/js/angular-admin/assets/css/styles-angular.css"],
                        providers: [auth_service_1.AuthService],
                        directives: [list_language_component_1.ListLanguageComponent,
                            router_1.ROUTER_DIRECTIVES]
                    }),
                    router_1.RouteConfig([
                        { path: "/languages", name: "Languages", component: list_language_component_1.ListLanguageComponent },
                        { path: "/edit-language/:id", name: "EditLanguage", component: edit_language_component_1.EditLanguageComponent },
                        { path: "/add-language", name: "AddLanguage", component: add_language_component_1.AddLanguageComponent },
                        { path: "/translates", name: "Translates", component: list_translation_component_1.ListTranslationComponent }
                    ]), 
                    __metadata('design:paramtypes', [auth_service_1.AuthService])
                ], AppComponent);
                return AppComponent;
            }());
            exports_1("AppComponent", AppComponent);
        }
    }
});
//# sourceMappingURL=app.component.js.map