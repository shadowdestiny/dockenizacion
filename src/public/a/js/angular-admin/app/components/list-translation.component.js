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
            },
            function (language_service_1_1) {
                language_service_1 = language_service_1_1;
            },
            function (category_service_1_1) {
                category_service_1 = category_service_1_1;
            }],
        execute: function() {
            ListTranslationComponent = (function () {
                function ListTranslationComponent(_translationService, _languageService, _categoryService) {
                    this._translationService = _translationService;
                    this._languageService = _languageService;
                    this._categoryService = _categoryService;
                    this.title = 'Manage Translations';
                    this.lang1 = 'en';
                    this.lang2 = 'es';
                    this.language1 = '';
                    this.language2 = '';
                    this.cat = ' ';
                    this.translations = new Array();
                    this.languages = new Array();
                    this.categories = new Array();
                }
                ListTranslationComponent.prototype.ngOnInit = function () {
                    this.getTranslations(this.lang1, this.lang2, this.cat);
                    this.getLanguages();
                    this.getCategories();
                };
                ListTranslationComponent.prototype.onSubmit = function () {
                    this.lang1 = this.language1;
                    this.lang2 = this.language2;
                    this.getTranslations(this.language1, this.language2, this.cat);
                    this.getLanguages();
                    this.getCategories();
                };
                ListTranslationComponent.prototype.getTranslations = function (l1, l2, cat) {
                    var _this = this;
                    this._translationService.getTranslations(l1, l2, cat)
                        .subscribe(function (result) {
                        _this.translations = result.translations;
                        console.log(result.translations);
                    }, function (error) {
                    });
                };
                ListTranslationComponent.prototype.getLanguages = function () {
                    var _this = this;
                    this._languageService.getLanguages()
                        .subscribe(function (result) {
                        _this.languages = result.languages;
                    }, function (error) {
                    });
                };
                ListTranslationComponent.prototype.getCategories = function () {
                    var _this = this;
                    this._categoryService.getCategories()
                        .subscribe(function (result) {
                        _this.categories = result.translation_categories;
                    }, function (error) {
                    });
                };
                ListTranslationComponent = __decorate([
                    core_1.Component({
                        selector: 'list-translations',
                        templateUrl: '/a/js/angular-admin/app/views/list-translations.html',
                        providers: [translation_service_1.TranslationService, language_service_1.LanguageService, category_service_1.CategoryService],
                        directives: [router_1.ROUTER_DIRECTIVES]
                    }), 
                    __metadata('design:paramtypes', [translation_service_1.TranslationService, language_service_1.LanguageService, category_service_1.CategoryService])
                ], ListTranslationComponent);
                return ListTranslationComponent;
            }());
            exports_1("ListTranslationComponent", ListTranslationComponent);
        }
    }
});
//# sourceMappingURL=list-translation.component.js.map