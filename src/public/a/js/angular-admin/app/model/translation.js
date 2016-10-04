System.register([], function(exports_1, context_1) {
    "use strict";
    var __moduleName = context_1 && context_1.id;
    var Translation;
    return {
        setters:[],
        execute: function() {
            Translation = (function () {
                function Translation(id, translationKey, translationCategory, category, description, translationDetail) {
                    this.id = id;
                    this.translationKey = translationKey;
                    this.translationCategory = translationCategory;
                    this.category = category;
                    this.description = description;
                    this.translationDetail = translationDetail;
                }
                return Translation;
            }());
            exports_1("Translation", Translation);
        }
    }
});
//# sourceMappingURL=translation.js.map