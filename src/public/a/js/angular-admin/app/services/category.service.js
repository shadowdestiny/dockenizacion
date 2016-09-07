System.register(['../config/config', "angular2/core", "angular2/http", "rxjs/add/operator/map", "../services/auth.service"], function(exports_1, context_1) {
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
    var config, core_1, http_1, auth_service_1;
    var CategoryService;
    return {
        setters:[
            function (config_1) {
                config = config_1;
            },
            function (core_1_1) {
                core_1 = core_1_1;
            },
            function (http_1_1) {
                http_1 = http_1_1;
            },
            function (_1) {},
            function (auth_service_1_1) {
                auth_service_1 = auth_service_1_1;
            }],
        execute: function() {
            CategoryService = (function () {
                function CategoryService(_http, _authService) {
                    this._http = _http;
                    this._authService = _authService;
                }
                CategoryService.prototype.getCategory = function (id) {
                    if (id === void 0) { id = null; }
                    var headers = this._authService.getHeaderBearer();
                    if (this._authService.isLoggedIn()) {
                        return this._http.get(config.api_url + 'translationCategories/' + id, { headers: headers }).map(function (res) { return res.json(); });
                    }
                };
                CategoryService.prototype.getCategories = function () {
                    var headers = this._authService.getHeaderBearer();
                    if (this._authService.isLoggedIn()) {
                        return this._http.get(config.api_url + 'translationCategories', { headers: headers }).map(function (res) { return res.json(); });
                    }
                };
                CategoryService.prototype.addCategory = function (category) {
                };
                CategoryService.prototype.updateCategory = function (category) {
                    var headers = this._authService.getHeaderBearer();
                    var json = JSON.stringify({ id: category.id,
                        categoryName: category.categoryName,
                        categoryCode: category.categoryCode,
                        description: category.description
                    });
                    if (this._authService.isLoggedIn()) {
                        return this._http.put(config.api_url + 'translationCategories/' + category.id, json, { headers: headers }).map(function (res) { return res.json(); });
                    }
                };
                CategoryService = __decorate([
                    core_1.Injectable(), 
                    __metadata('design:paramtypes', [http_1.Http, auth_service_1.AuthService])
                ], CategoryService);
                return CategoryService;
            }());
            exports_1("CategoryService", CategoryService);
        }
    }
});
//# sourceMappingURL=category.service.js.map