System.register(['angular2/core', "angular2/router", "../services/translation.service", "../model/translation", "../services/language.service", "../services/category.service", "../model/category"], function(exports_1, context_1) {
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
    var core_1, router_1, translation_service_1, translation_1, language_service_1, category_service_1, category_1;
    var EditTranslationComponent;
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
            function (translation_1_1) {
                translation_1 = translation_1_1;
            },
            function (language_service_1_1) {
                language_service_1 = language_service_1_1;
            },
            function (category_service_1_1) {
                category_service_1 = category_service_1_1;
            },
            function (category_1_1) {
                category_1 = category_1_1;
            }],
        execute: function() {
            EditTranslationComponent = (function () {
                function EditTranslationComponent(_translationService, _categoryService, _languageService, _routeParams) {
                    this._translationService = _translationService;
                    this._categoryService = _categoryService;
                    this._languageService = _languageService;
                    this._routeParams = _routeParams;
                    this.cat = new category_1.Category(0, '', '', '');
                    this.translation = new translation_1.Translation(0, '', 0, this.cat, '');
                }
                EditTranslationComponent.prototype.ngOnInit = function () {
                    this.getLanguages();
                    this.getCategories();
                    this.getTranslationById();
                };
                EditTranslationComponent.prototype.editTranslation = function () {
                };
                EditTranslationComponent.prototype.getLanguages = function () {
                    var _this = this;
                    this._languageService.getLanguages()
                        .subscribe(function (result) {
                        _this.languages = result.languages;
                    }, function (error) {
                    });
                };
                EditTranslationComponent.prototype.getCategories = function () {
                    var _this = this;
                    this._categoryService.getCategories()
                        .subscribe(function (result) {
                        _this.categories = result.translation_categories;
                    }, function (error) {
                    });
                };
                EditTranslationComponent.prototype.getTranslationById = function () {
                    var _this = this;
                    this.id = this._routeParams.get("id");
                    this._translationService.getTranslation(this.id)
                        .subscribe(function (result) {
                        _this.translation = result.translation;
                        console.log(result.translation);
                    }, function (error) {
                    });
                };
                EditTranslationComponent = __decorate([
                    core_1.Component({
                        selector: 'edit-translation',
                        templateUrl: '/a/js/angular-admin/app/views/edit-translation.html',
                        providers: [translation_service_1.TranslationService, category_service_1.CategoryService, language_service_1.LanguageService],
                        directives: [router_1.ROUTER_DIRECTIVES]
                    }), 
                    __metadata('design:paramtypes', [translation_service_1.TranslationService, category_service_1.CategoryService, language_service_1.LanguageService, router_1.RouteParams])
                ], EditTranslationComponent);
                return EditTranslationComponent;
            }());
            exports_1("EditTranslationComponent", EditTranslationComponent);
        }
    }
});
//# sourceMappingURL=edit-translation.component.js.map