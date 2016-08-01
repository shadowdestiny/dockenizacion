System.register(["angular2/core", "angular2/http", "angular2/router", '../config/config'], function(exports_1, context_1) {
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
    var core_1, http_1, router_1, config;
    var AuthService;
    return {
        setters:[
            function (core_1_1) {
                core_1 = core_1_1;
            },
            function (http_1_1) {
                http_1 = http_1_1;
            },
            function (router_1_1) {
                router_1 = router_1_1;
            },
            function (config_1) {
                config = config_1;
            }],
        execute: function() {
            AuthService = (function () {
                function AuthService(_http, _router) {
                    this._http = _http;
                    this._router = _router;
                    this.loggedIn = false;
                }
                AuthService.prototype.login = function () {
                    var _this = this;
                    var headers = new http_1.Headers();
                    var user = config.user_api;
                    var pass = config.pass_api;
                    headers.append('Content-type', 'application/json');
                    headers.append('Authorization', 'Basic ' + btoa(user + ":" + pass));
                    return this._http
                        .post(config.api_url + 'users/authenticate', JSON.stringify({ user: user, pass: pass }), { headers: headers })
                        .map(function (res) { return res.json(); })
                        .map(function (res) {
                        if (!res.error) {
                            _this.loggedIn = true;
                            config.token = res.data.token;
                        }
                        else {
                            _this._router.navigate(['/translate']);
                        }
                    });
                };
                AuthService.prototype.isLoggedIn = function () {
                    return this.loggedIn;
                };
                AuthService.prototype.getToken = function () {
                    return this.loggedIn ? config.token : null;
                };
                AuthService.prototype.getHeaderBearer = function () {
                    var headers = new http_1.Headers();
                    headers.append('Authorization', "Bearer " + config.token);
                    return headers;
                };
                AuthService = __decorate([
                    core_1.Injectable(), 
                    __metadata('design:paramtypes', [http_1.Http, router_1.Router])
                ], AuthService);
                return AuthService;
            }());
            exports_1("AuthService", AuthService);
        }
    }
});
//# sourceMappingURL=auth.service.js.map