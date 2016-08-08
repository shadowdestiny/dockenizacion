System.register([], function(exports_1, context_1) {
    "use strict";
    var __moduleName = context_1 && context_1.id;
    var Category;
    return {
        setters:[],
        execute: function() {
            Category = (function () {
                function Category(id, categoryCode, categoryName, description) {
                    this.id = id;
                    this.categoryCode = categoryCode;
                    this.categoryName = categoryName;
                    this.description = description;
                }
                return Category;
            }());
            exports_1("Category", Category);
        }
    }
});
//# sourceMappingURL=category.js.map