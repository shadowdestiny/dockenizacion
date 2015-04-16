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
