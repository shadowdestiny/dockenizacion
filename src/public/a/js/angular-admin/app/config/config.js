System.register([], function(exports_1, context_1) {
    "use strict";
    var __moduleName = context_1 && context_1.id;
    var api_url, user_api, pass_api, token;
    return {
        setters:[],
        execute: function() {
            exports_1("api_url", api_url = 'http://localhost:8081/');
            exports_1("user_api", user_api = 'euromillions');
            exports_1("pass_api", pass_api = 'euromillions190');
            exports_1("token", token = '');
        }
    }
});
//# sourceMappingURL=config.js.map