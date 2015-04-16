﻿/*
* jQuery Tooltip plugin 1.3
*
* http://bassistance.de/jquery-plugins/jquery-plugin-tooltip/
* http://docs.jquery.com/Plugins/Tooltip
*
* Copyright (c) 2006 - 2008 JÃ¶rn Zaefferer
*
* $Id: jquery.tooltip.js 5741 2008-06-21 15:22:16Z joern.zaefferer $
* 
* Dual licensed under the MIT and GPL licenses:
*   http://www.opensource.org/licenses/mit-license.php
*   http://www.gnu.org/licenses/gpl.html
*/
; (function ($) {
    var helper = {}, current, title, tID, IE = $.browser.msie && /MSIE\s(5\.5|6\.)/.test(navigator.userAgent), track = false; $.tooltip = { blocked: false, defaults: { delay: 200, fade: false, showURL: true, extraClass: "", top: 15, left: 15, id: "tooltip" }, block: function () { $.tooltip.blocked = !$.tooltip.blocked; } }; $.fn.extend({ tooltip: function (settings) { settings = $.extend({}, $.tooltip.defaults, settings); createHelper(settings); return this.each(function () { $.data(this, "tooltip", settings); this.tOpacity = helper.parent.css("opacity"); this.tooltipText = this.title; $(this).removeAttr("title"); this.alt = ""; }).mouseover(save).mouseout(hide).click(hide); }, fixPNG: IE ? function () { return this.each(function () { var image = $(this).css('backgroundImage'); if (image.match(/^url\(["']?(.*\.png)["']?\)$/i)) { image = RegExp.$1; $(this).css({ 'backgroundImage': 'none', 'filter': "progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled=true, sizingMethod=crop, src='" + image + "')" }).each(function () { var position = $(this).css('position'); if (position != 'absolute' && position != 'relative') $(this).css('position', 'relative'); }); } }); } : function () { return this; }, unfixPNG: IE ? function () { return this.each(function () { $(this).css({ 'filter': '', backgroundImage: '' }); }); } : function () { return this; }, hideWhenEmpty: function () { return this.each(function () { $(this)[$(this).html() ? "show" : "hide"](); }); }, url: function () { return this.attr('href') || this.attr('src'); } }); function createHelper(settings) { if (helper.parent) return; helper.parent = $('<div id="' + settings.id + '"><h3></h3><div class="body"></div><div class="url"></div></div>').appendTo(document.body).hide(); if ($.fn.bgiframe) helper.parent.bgiframe(); helper.title = $('h3', helper.parent); helper.body = $('div.body', helper.parent); helper.url = $('div.url', helper.parent); } function settings(element) { return $.data(element, "tooltip"); } function handle(event) {
        if (settings(this).delay) tID = setTimeout(show, settings(this).delay); else
            show(); track = !!settings(this).track; $(document.body).bind('mousemove', update); update(event);
    } function save() {
        if ($.tooltip.blocked || this == current || (!this.tooltipText && !settings(this).bodyHandler)) return; current = this; title = this.tooltipText; if (settings(this).bodyHandler) { helper.title.hide(); var bodyContent = settings(this).bodyHandler.call(this); if (bodyContent.nodeType || bodyContent.jquery) { helper.body.empty().append(bodyContent) } else { helper.body.html(bodyContent); } helper.body.show(); } else if (settings(this).showBody) { var parts = title.split(settings(this).showBody); helper.title.html(parts.shift()).show(); helper.body.empty(); for (var i = 0, part; (part = parts[i]); i++) { if (i > 0) helper.body.append("<br/>"); helper.body.append(part); } helper.body.hideWhenEmpty(); } else { helper.title.html(title).show(); helper.body.hide(); } if (settings(this).showURL && $(this).url()) helper.url.html($(this).url().replace('http://', '')).show(); else
            helper.url.hide(); helper.parent.addClass(settings(this).extraClass); if (settings(this).fixPNG) helper.parent.fixPNG(); handle.apply(this, arguments);
    } function show() {
        tID = null; if ((!IE || !$.fn.bgiframe) && settings(current).fade) {
            if (helper.parent.is(":animated")) helper.parent.stop().show().fadeTo(settings(current).fade, current.tOpacity); else
                helper.parent.is(':visible') ? helper.parent.fadeTo(settings(current).fade, current.tOpacity) : helper.parent.fadeIn(settings(current).fade);
        } else { helper.parent.show(); } update();
    } function update(event) { if ($.tooltip.blocked) return; if (event && event.target.tagName == "OPTION") { return; } if (!track && helper.parent.is(":visible")) { $(document.body).unbind('mousemove', update) } if (current == null) { $(document.body).unbind('mousemove', update); return; } helper.parent.removeClass("viewport-right").removeClass("viewport-bottom"); var left = helper.parent[0].offsetLeft; var top = helper.parent[0].offsetTop; if (event) { left = event.pageX + settings(current).left; top = event.pageY + settings(current).top; var right = 'auto'; if (settings(current).positionLeft) { right = $(window).width() - left; left = 'auto'; } helper.parent.css({ left: left, right: right, top: top }); } var v = viewport(), h = helper.parent[0]; if (v.x + v.cx < h.offsetLeft + h.offsetWidth) { left -= h.offsetWidth + 20 + settings(current).left; helper.parent.css({ left: left + 'px' }).addClass("viewport-right"); } if (v.y + v.cy < h.offsetTop + h.offsetHeight) { top -= h.offsetHeight + 20 + settings(current).top; helper.parent.css({ top: top + 'px' }).addClass("viewport-bottom"); } } function viewport() { return { x: $(window).scrollLeft(), y: $(window).scrollTop(), cx: $(window).width(), cy: $(window).height() }; } function hide(event) {
        if ($.tooltip.blocked) return; if (tID) clearTimeout(tID); current = null; var tsettings = settings(this); function complete() { helper.parent.removeClass(tsettings.extraClass).hide().css("opacity", ""); } if ((!IE || !$.fn.bgiframe) && tsettings.fade) {
            if (helper.parent.is(':animated')) helper.parent.stop().fadeTo(tsettings.fade, 0, complete); else
                helper.parent.stop().fadeOut(tsettings.fade, complete);
        } else
            complete(); if (settings(this).fixPNG) helper.parent.unfixPNG();
    } 
})(jQuery);