System.register(['angular2/core', "angular2/router", "../services/language.service"], function(exports_1) {
    var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
        var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
        if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
        else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
        return c > 3 && r && Object.defineProperty(target, key, r), r;
    };
    var __metadata = (this && this.__metadata) || function (k, v) {
        if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
    };
    var core_1, router_1, language_service_1;
    var ListLanguageComponent;
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
            }],
        execute: function() {
            ListLanguageComponent = (function () {
                function ListLanguageComponent(_languageService) {
                    this._languageService = _languageService;
                    this.title = 'Manage Languages';
                }
                ListLanguageComponent.prototype.ngOnInit = function () {
                    this.getLanguages();
                };
                ListLanguageComponent.prototype.getLanguages = function () {
                    var _this = this;
                    this._languageService.getLanguages()
                        .subscribe(function (result) {
                        _this.languages = result.languages;
                    }, function (error) {
                    });
                };
                ListLanguageComponent = __decorate([
                    core_1.Component({
                        selector: 'list-languages',
                        templateUrl: '/a/js/angular-admin/app/views/list-languages.html',
                        providers: [language_service_1.LanguageService],
                        directives: [router_1.ROUTER_DIRECTIVES]
                    }), 
                    __metadata('design:paramtypes', [language_service_1.LanguageService])
                ], ListLanguageComponent);
                return ListLanguageComponent;
            })();
            exports_1("ListLanguageComponent", ListLanguageComponent);
        }
    }
});
//# sourceMappingURL=list-language.component.js.map