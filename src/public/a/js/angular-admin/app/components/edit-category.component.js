System.register(['angular2/core', "angular2/router", "../services/category.service", "../model/category"], function(exports_1, context_1) {
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
    var core_1, router_1, category_service_1, category_1;
    var EditCategoryComponent;
    return {
        setters:[
            function (core_1_1) {
                core_1 = core_1_1;
            },
            function (router_1_1) {
                router_1 = router_1_1;
            },
            function (category_service_1_1) {
                category_service_1 = category_service_1_1;
            },
            function (category_1_1) {
                category_1 = category_1_1;
            }],
        execute: function() {
            EditCategoryComponent = (function () {
                function EditCategoryComponent(_categoryService, _routeParams) {
                    this._categoryService = _categoryService;
                    this._routeParams = _routeParams;
                    this.title = 'Edit Category';
                    this.status = false;
                    this.category = new category_1.Category(0, '', '', '');
                }
                EditCategoryComponent.prototype.ngOnInit = function () {
                    this.getCategoryById();
                };
                EditCategoryComponent.prototype.getCategoryById = function () {
                    var _this = this;
                    this.id = this._routeParams.get("id");
                    this._categoryService.getCategory(this.id)
                        .subscribe(function (result) {
                        _this.category = result.translation_category;
                    }, function (error) {
                    });
                };
                EditCategoryComponent.prototype.onSubmit = function () {
                    var _this = this;
                    this._categoryService.updateCategory(this.category)
                        .subscribe(function (result) {
                        _this.status = true;
                    }, function (error) {
                        _this.status = false;
                    });
                };
                EditCategoryComponent = __decorate([
                    core_1.Component({
                        selector: 'edit-category',
                        templateUrl: '/a/js/angular-admin/app/views/edit-category.html',
                        providers: [category_service_1.CategoryService],
                        directives: [router_1.ROUTER_DIRECTIVES]
                    }), 
                    __metadata('design:paramtypes', [category_service_1.CategoryService, router_1.RouteParams])
                ], EditCategoryComponent);
                return EditCategoryComponent;
            }());
            exports_1("EditCategoryComponent", EditCategoryComponent);
        }
    }
});
//# sourceMappingURL=edit-category.component.js.map