System.register(["angular2/core", "angular2/http", "rxjs/add/operator/map", "../services/auth.service", '../config/config'], function(exports_1, context_1) {
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
    var core_1, http_1, auth_service_1, config;
    var LanguageService;
    return {
        setters:[
            function (core_1_1) {
                core_1 = core_1_1;
            },
            function (http_1_1) {
                http_1 = http_1_1;
            },
            function (_1) {},
            function (auth_service_1_1) {
                auth_service_1 = auth_service_1_1;
            },
            function (config_1) {
                config = config_1;
            }],
        execute: function() {
            LanguageService = (function () {
                function LanguageService(_http, _authService) {
                    this._http = _http;
                    this._authService = _authService;
                }
                LanguageService.prototype.getLanguage = function (id) {
                    if (id === void 0) { id = null; }
                    var headers = this._authService.getHeaderBearer();
                    if (this._authService.isLoggedIn()) {
                        return this._http.get(config.api_url + 'languages/' + id, { headers: headers }).map(function (res) { return res.json(); });
                    }
                };
                LanguageService.prototype.updateLanguage = function (language) {
                    var headers = this._authService.getHeaderBearer();
                    var json = JSON.stringify({ id: language.id,
                        ccode: language.ccode,
                        defaultLocale: language.defaultLocale,
                        active: language.active
                    });
                    if (this._authService.isLoggedIn()) {
                        return this._http.put(config.api_url + 'languages/' + language.id, json, { headers: headers }).map(function (res) { return res.json(); });
                    }
                };
                LanguageService.prototype.addLanguage = function (language) {
                    var headers = this._authService.getHeaderBearer();
                    var json = JSON.stringify({ id: language.id,
                        ccode: language.ccode,
                        defaultLocale: language.defaultLocale,
                        active: 0
                    });
                    if (this._authService.isLoggedIn()) {
                        return this._http.post(config.api_url + 'languages', json, { headers: headers }).map(function (res) { return res.json(); });
                    }
                };
                LanguageService.prototype.getLanguages = function () {
                    var headers = this._authService.getHeaderBearer();
                    if (this._authService.isLoggedIn()) {
                        return this._http.get(config.api_url + 'languages', { headers: headers }).map(function (res) { return res.json(); });
                    }
                };
                LanguageService = __decorate([
                    core_1.Injectable(), 
                    __metadata('design:paramtypes', [http_1.Http, auth_service_1.AuthService])
                ], LanguageService);
                return LanguageService;
            }());
            exports_1("LanguageService", LanguageService);
        }
    }
});
//# sourceMappingURL=language.service.js.map