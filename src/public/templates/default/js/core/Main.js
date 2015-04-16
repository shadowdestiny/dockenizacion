function AddCommas(nStr) {
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}

/**
 * @classDescription Create Namespaces from the base 'Casino' namespace
 * @author adpi
 * @function
 * @param {String} ns the namespace to create
 */
var EuroMillions = EuroMillions || {};

EuroMillions.namespace = function (name, separator, container) {
	var ns = name.split(separator || '.'), o = container || window, i, len;
	for (i = 0, len = ns.length; i < len; i++) {
		o = o[ns[i]] = o[ns[i]] || {};
	}
	return o;
};

/**
 * This code will create a jquery plugin from a JS object
 * Info on the practice can be found here: http://alexsexton.com/?p=51
 *
 * Once a js object has been created it can be turned into a Jquery plugin by using:
 *
 * $.plugin('pluginName', PluginJSObject);
 *
 * You can then use it like:
 *
 * $('some-selector').pluginName(initParams);
 *
 * The initial object can be retrieved from the element's data source if needed by:
 *
 * var pluginObjectInstance = $('some-selector').data('pluginName');
 */
$.plugin = function(name, object) {
	$.fn[name] = function(options) {
		var args = Array.prototype.slice.call(arguments, 1);
		return this.each(function() {
			var instance = $.data(this, name);
			if (instance) {
				instance[options].apply(instance, args);
			} else {
				instance = $.data(this, name, new object(options, this));
			}
		});
	};
};
