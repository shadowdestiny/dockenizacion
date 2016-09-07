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
    var TranslationService;
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
            TranslationService = (function () {
                function TranslationService(_http, _authService) {
                    this._http = _http;
                    this._authService = _authService;
                }
                TranslationService.prototype.getTranslations = function (lang1, lang2, category) {
                    var headers = this._authService.getHeaderBearer();
                    if (this._authService.isLoggedIn()) {
                        if (category != ' ') {
                            return this._http.get(config.api_url + 'translations?lang1=' + lang1 + '&lang2=' + lang2 + '&having={"translationCategory_id":' + category + '}', { headers: headers }).map(function (res) { return res.json(); });
                        }
                        else {
                            return this._http.get(config.api_url + 'translations/?lang1=' + lang1 + '&lang2=' + lang2, { headers: headers }).map(function (res) { return res.json(); });
                        }
                    }
                };
                TranslationService.prototype.getTranslation = function (id) {
                    if (id === void 0) { id = null; }
                    var headers = this._authService.getHeaderBearer();
                    if (this._authService.isLoggedIn()) {
                        return this._http.get(config.api_url + 'translations/' + id, { headers: headers }).map(function (res) { return res.json(); });
                    }
                };
                TranslationService = __decorate([
                    core_1.Injectable(), 
                    __metadata('design:paramtypes', [http_1.Http, auth_service_1.AuthService])
                ], TranslationService);
                return TranslationService;
            }());
            exports_1("TranslationService", TranslationService);
        }
    }
});
//# sourceMappingURL=translation.service.js.map