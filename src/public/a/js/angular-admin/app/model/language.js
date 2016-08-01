System.register([], function(exports_1, context_1) {
    "use strict";
    var __moduleName = context_1 && context_1.id;
    var Language;
    return {
        setters:[],
        execute: function() {
            Language = (function () {
                function Language(id, ccode, defaultLocale, active) {
                    this.id = id;
                    this.ccode = ccode;
                    this.defaultLocale = defaultLocale;
                    this.active = active;
                }
                return Language;
            }());
            exports_1("Language", Language);
        }
    }
});
//# sourceMappingURL=language.js.map