System.register(['angular2/core', "angular2/router", "../services/translation.service"], function(exports_1, context_1) {
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
    var core_1, router_1, translation_service_1;
    var ListTranslationComponent;
    return {
        setters:[
            function (core_1_1) {
                core_1 = core_1_1;
            },
            function (router_1_1) {
                router_1 = router_1_1;
            },
            function (translation_service_1_1) {
                translation_service_1 = translation_service_1_1;
            }],
        execute: function() {
            ListTranslationComponent = (function () {
                function ListTranslationComponent(_translationService) {
                    this._translationService = _translationService;
                }
                ListTranslationComponent.prototype.ngOnInit = function () {
                };
                ListTranslationComponent = __decorate([
                    core_1.Component({
                        selector: 'list-languages',
                        templateUrl: '/a/js/angular-admin/app/views/list-translations.html',
                        providers: [translation_service_1.TranslationService],
                        directives: [router_1.ROUTER_DIRECTIVES]
                    }), 
                    __metadata('design:paramtypes', [translation_service_1.TranslationService])
                ], ListTranslationComponent);
                return ListTranslationComponent;
            }());
            exports_1("ListTranslationComponent", ListTranslationComponent);
        }
    }
});
//# sourceMappingURL=list-translation.component.js.map