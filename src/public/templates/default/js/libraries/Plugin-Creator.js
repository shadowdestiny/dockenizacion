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
