System.register(['angular2/core', "angular2/router", "../services/translation.service", "../services/language.service", "../services/category.service"], function(exports_1, context_1) {
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
    var core_1, router_1, translation_service_1, language_service_1, category_service_1;
    var AddTranslationComponent;
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
            },
            function (language_service_1_1) {
                language_service_1 = language_service_1_1;
            },
            function (category_service_1_1) {
                category_service_1 = category_service_1_1;
            }],
        execute: function() {
            AddTranslationComponent = (function () {
                function AddTranslationComponent(_translationService, _categoryService, _languageService, _routeParams) {
                    this._translationService = _translationService;
                    this._categoryService = _categoryService;
                    this._languageService = _languageService;
                    this._routeParams = _routeParams;
                }
                AddTranslationComponent.prototype.ngOnInit = function () {
                    this.getLanguages();
                    this.getCategories();
                };
                AddTranslationComponent.prototype.addTranslation = function () {
                };
                AddTranslationComponent.prototype.getLanguages = function () {
                    var _this = this;
                    this._languageService.getLanguages()
                        .subscribe(function (result) {
                        _this.languages = result.languages;
                    }, function (error) {
                    });
                };
                AddTranslationComponent.prototype.getCategories = function () {
                    var _this = this;
                    this._categoryService.getCategories()
                        .subscribe(function (result) {
                        _this.categories = result.translation_categories;
                    }, function (error) {
                    });
                };
                AddTranslationComponent = __decorate([
                    core_1.Component({
                        selector: 'add-translation',
                        templateUrl: '/a/js/angular-admin/app/views/add-translation.html',
                        providers: [translation_service_1.TranslationService, category_service_1.CategoryService, language_service_1.LanguageService],
                        directives: [router_1.ROUTER_DIRECTIVES]
                    }), 
                    __metadata('design:paramtypes', [translation_service_1.TranslationService, category_service_1.CategoryService, language_service_1.LanguageService, router_1.RouteParams])
                ], AddTranslationComponent);
                return AddTranslationComponent;
            }());
            exports_1("AddTranslationComponent", AddTranslationComponent);
        }
    }
});
//# sourceMappingURL=add-translation.component.js.map