//  This file is part of the jQuery formatCurrency Plugin.
//
//    The jQuery formatCurrency Plugin is free software: you can redistribute it
//    and/or modify it under the terms of the GNU General Public License as published
//    by the Free Software Foundation, either version 3 of the License, or
//    (at your option) any later version.

//    The jQuery formatCurrency Plugin is distributed in the hope that it will
//    be useful, but WITHOUT ANY WARRANTY; without even the implied warranty
//    of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//    GNU General Public License for more details.
//
//    You should have received a copy of the GNU General Public License along with
//    the jQuery formatCurrency Plugin.  If not, see <http://www.gnu.org/licenses/>.

(function($) {

	$.formatCurrency.regions['en-US'] = {
		symbol: 'US$',
		positiveFormat: '%s %n',
		negativeFormat: '-%s %n',
		decimalSymbol: '.',
		digitGroupSymbol: ',',
		groupDigits: true
	};

	$.formatCurrency.regions['ja-JP'] = {
		symbol: '¥',
		positiveFormat: '%s %n',
		negativeFormat: '-%s %n',
		decimalSymbol: '.',
		digitGroupSymbol: ',',
		groupDigits: true
	};

	$.formatCurrency.regions['bg-BG'] = {
		symbol: 'лв',
		positiveFormat: '%n %s',
		negativeFormat: '-%n %s',
		decimalSymbol: ',',
		digitGroupSymbol: ' ',
		groupDigits: true
	};

	$.formatCurrency.regions['cs-CZ'] = {
		symbol: 'Kč',
		positiveFormat: '%s %n',
		negativeFormat: '-%s %n',
		decimalSymbol: ',',
		digitGroupSymbol: ' ',
		groupDigits: true
	};

	$.formatCurrency.regions['da-DK'] = {
		symbol: 'Kr DDK',
		positiveFormat: '%n %s',
		negativeFormat: '-%n %s',
		decimalSymbol: ',',
		digitGroupSymbol: '.',
		groupDigits: true
	};

	$.formatCurrency.regions['en-GB'] = {
		symbol: '£',
		positiveFormat: '%s %n',
		negativeFormat: '-%s %n',
		decimalSymbol: '.',
		digitGroupSymbol: ',',
		groupDigits: true
	};

	$.formatCurrency.regions['hu-HU'] = {
		symbol: 'Ft.',
		positiveFormat: '%n %s',
		negativeFormat: '-%n %s',
		decimalSymbol: ',',
		digitGroupSymbol: ' ',
		groupDigits: true
	};

	$.formatCurrency.regions['lt-LT'] = {
		symbol: 'Lt',
		positiveFormat: '%n %s',
		negativeFormat: '-%n %s',
		decimalSymbol: ',',
		digitGroupSymbol: '.',
		groupDigits: true
	};

	$.formatCurrency.regions['pl-PL'] = {
		symbol: 'zł',
		positiveFormat: '%s %n',
		negativeFormat: '-%s %n',
		decimalSymbol: ',',
		digitGroupSymbol: ' ',
		groupDigits: true
	};

	$.formatCurrency.regions['ro-RO'] = {
		symbol: 'lei',
		positiveFormat: '%n %s',
		negativeFormat: '-%n %s',
		decimalSymbol: ',',
		digitGroupSymbol: '.',
		groupDigits: true
	};

	$.formatCurrency.regions['sv-SE'] = {
		symbol: 'Kr SEK',
		positiveFormat: '%n %s',
		negativeFormat: '-%n %s',
		decimalSymbol: ',',
		digitGroupSymbol: '.',
		groupDigits: true
	};

	$.formatCurrency.regions['de-CH'] = {
		symbol: 'CHF',
		positiveFormat: '%s %n',
		negativeFormat: '-%s %n',
		decimalSymbol: '.',
		digitGroupSymbol: '\'',
		groupDigits: true
	};

	$.formatCurrency.regions['nb-NO'] = {
		symbol: 'kr NOK',
		positiveFormat: '%n %s',
		negativeFormat: '-%n %s',
		decimalSymbol: ',',
		digitGroupSymbol: ' ',
		groupDigits: true
	};

	$.formatCurrency.regions['hr-HR'] = {
		symbol: 'Kn',
		positiveFormat: '%s %n',
		negativeFormat: '-%s %n',
		decimalSymbol: ',',
		digitGroupSymbol: '.',
		groupDigits: true
	};

	$.formatCurrency.regions['ru-RU'] = {
		symbol: 'pyб.',
		positiveFormat: '%n %s',
		negativeFormat: '-%n %s',
		decimalSymbol: ',',
		digitGroupSymbol: ' ',
		groupDigits: true
	};

	$.formatCurrency.regions['tr-TR'] = {
		symbol: 'TL',
		positiveFormat: '%n %s',
		negativeFormat: '-%n %s',
		decimalSymbol: ',',
		digitGroupSymbol: '.',
		groupDigits: true
	};

	$.formatCurrency.regions['en-AU'] = {
		symbol: 'A$',
		positiveFormat: '%s %n',
		negativeFormat: '-%s %n',
		decimalSymbol: '.',
		digitGroupSymbol: ',',
		groupDigits: true
	};

	$.formatCurrency.regions['pt-BR'] = {
		symbol: 'R$',
		positiveFormat: '%s %n',
		negativeFormat: '-%s %n',
		decimalSymbol: ',',
		digitGroupSymbol: '.',
		groupDigits: true
	};

	$.formatCurrency.regions['en-CA'] = {
		symbol: 'C$',
		positiveFormat: '%s %n',
		negativeFormat: '-%s %n',
		decimalSymbol: '.',
		digitGroupSymbol: ',',
		groupDigits: true
	};

	$.formatCurrency.regions['zh-CN'] = {
		symbol: 'RMB', //￥
		positiveFormat: '%s %n',
		negativeFormat: '-%s %n',
		decimalSymbol: '.',
		digitGroupSymbol: ',',
		groupDigits: true
	};

	$.formatCurrency.regions['zh-HK'] = {
		symbol: 'HK$',
		positiveFormat: '%s %n',
		negativeFormat: '-%s %n',
		decimalSymbol: '.',
		digitGroupSymbol: ',',
		groupDigits: true
	};

	$.formatCurrency.regions['id-ID'] = {
		symbol: 'Rp',
		positiveFormat: '%s %n',
		negativeFormat: '-%s %n',
		decimalSymbol: ',',
		digitGroupSymbol: '.',
		groupDigits: true
	};

	$.formatCurrency.regions['en-IN'] = {
		symbol: '₹',
		positiveFormat: '%s %n',
		negativeFormat: '-%s %n',
		decimalSymbol: '.',
		digitGroupSymbol: ',',
		groupDigits: true
	};

	$.formatCurrency.regions['ko-KR'] = {
		symbol: '₩',
		positiveFormat: '%s %n',
		negativeFormat: '-%s %n',
		decimalSymbol: '.',
		digitGroupSymbol: ',',
		groupDigits: true
	};

	$.formatCurrency.regions['es-MX'] = {
		symbol: 'MEX$',
		positiveFormat: '%s %n',
		negativeFormat: '-%s %n',
		decimalSymbol: '.',
		digitGroupSymbol: ',',
		groupDigits: true
	};

	$.formatCurrency.regions['ms-MY'] = {
		symbol: 'RM ',
		positiveFormat: '%s%n',
		negativeFormat: '(%s%n)',
		decimalSymbol: '.',
		digitGroupSymbol: ',',
		groupDigits: true
	};

	$.formatCurrency.regions['en-NZ'] = {
		symbol: 'NZ$',
		positiveFormat: '%s %n',
		negativeFormat: '-%s %n',
		decimalSymbol: '.',
		digitGroupSymbol: ',',
		groupDigits: true
	};

	$.formatCurrency.regions['en-PH'] = {
		symbol: '₱',
		positiveFormat: '%s %n',
		negativeFormat: '-%s %n',
		decimalSymbol: '.',
		digitGroupSymbol: ',',
		groupDigits: true
	};

	$.formatCurrency.regions['en-SG'] = {
		symbol: '$',
		positiveFormat: '%s %n',
		negativeFormat: '-%s %n',
		decimalSymbol: '.',
		digitGroupSymbol: ',',
		groupDigits: true
	};

	$.formatCurrency.regions['th-TH'] = {
		symbol: '฿',
		positiveFormat: '%s %n',
		negativeFormat: '-%s %n',
		decimalSymbol: '.',
		digitGroupSymbol: ',',
		groupDigits: true
	};

	$.formatCurrency.regions['en-ZA'] = {
		symbol: 'R',
		positiveFormat: '%s %n',
		negativeFormat: '%s-%n',
		decimalSymbol: ',',
		digitGroupSymbol: ' ',
		groupDigits: true
	};

	$.formatCurrency.regions['de-DE'] = {
		symbol: '€',
		positiveFormat: '%n %s',
		negativeFormat: '-%n %s',
		decimalSymbol: ',',
		digitGroupSymbol: '.',
		groupDigits: true
	};

})(jQuery);
