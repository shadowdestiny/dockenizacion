System.register(['angular2/core', "angular2/router", "../services/language.service", "../model/language"], function(exports_1, context_1) {
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
    var core_1, router_1, language_service_1, language_1;
    var AddLanguageComponent;
    return {
        setters:[
            function (core_1_1) {
                core_1 = core_1_1;
            },
            function (router_1_1) {
                router_1 = router_1_1;
            },
            function (language_service_1_1) {
                language_service_1 = language_service_1_1;
            },
            function (language_1_1) {
                language_1 = language_1_1;
            }],
        execute: function() {
            AddLanguageComponent = (function () {
                function AddLanguageComponent(_languageService, _routeParams) {
                    this._languageService = _languageService;
                    this._routeParams = _routeParams;
                    this.status = false;
                    this.language = new language_1.Language(0, '', '', true);
                }
                AddLanguageComponent.prototype.addLanguage = function () {
                    var _this = this;
                    this._languageService.addLanguage(this.language)
                        .subscribe(function (result) {
                        _this.status = true;
                    }, function (error) {
                        _this.status = false;
                    });
                };
                AddLanguageComponent = __decorate([
                    core_1.Component({
                        selector: 'add-language',
                        templateUrl: '/a/js/angular-admin/app/views/add-language.html',
                        providers: [language_service_1.LanguageService],
                        directives: [router_1.ROUTER_DIRECTIVES]
                    }), 
                    __metadata('design:paramtypes', [language_service_1.LanguageService, router_1.RouteParams])
                ], AddLanguageComponent);
                return AddLanguageComponent;
            }());
            exports_1("AddLanguageComponent", AddLanguageComponent);
        }
    }
});
//# sourceMappingURL=add-language.component.js.map