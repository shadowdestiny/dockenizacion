<!--[if lt IE 9]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<!--[if IE 9]>
<script src="/w/js/vendor/jquery.placeholder.min.js"></script>
<script>
$(function(){$('input, textarea').placeholder();});
</script>
<![endif]-->
<script src="/w/js/vendor/svgxuse.min.js" async></script> {# Render SVG for IE and problematic browsers #} 
<script src="/w/js/vendor/jquery.cookie.js"></script>
<script src="/w/js/vendor/jquery.countdown.min.js"></script>
<script src="/w/js/vendor/picturefill.min.js" async></script>
<script src="/w/js/vendor/easyModal.min.js"></script>

<script>
$(document).bind("mobileinit", function(){
    $.mobile.ajaxEnabled=false;
    $.mobile.pushStateEnabled=false; // Disable Ajax
    $.mobile.ignoreContentEnabled=true; // Unable disactivation of Jquery behaviour on anchors, by adding on the parent/container of the links  data-ajax="false"
});

/* Browser detection by feature detection --> http://stackoverflow.com/questions/9847580/how-to-detect-safari-chrome-ie-firefox-and-opera-browser */
var isOpera = (!!window.opr && !!opr.addons) || !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0; // Opera 8.0+
var isFirefox = typeof InstallTrigger !== 'undefined'; // Firefox 1.0+
var isSafari = Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0; // At least Safari 3+: "[object HTMLElementConstructor]"
var isIE = /*@cc_on!@*/false || !!document.documentMode; // Internet Explorer 6-11
var isEdge = !isIE && !!window.StyleMedia;  // Edge 20+
var isChrome = !!window.chrome && !!window.chrome.webstore; // Chrome 1+
var isBlink = (isChrome || isOpera) && !!window.CSS; // Blink engine detection

/* DRAW TIME */
var remain_time = '<?php echo isset($time_to_remain_draw) ? $time_to_remain_draw : ""; ?>';
var time_out_closing_modal = '<?php echo !empty($timeout_to_closing_modal) ? $timeout_to_closing_modal : "";?>';
var minutes_to_close = '<?php echo !empty($minutes_to_close) ? (int) $minutes_to_close : "";?>';
var last_minute = '<?php echo isset($last_minute) ?  $last_minute : ""; ?>';
var draw_date = '<?php echo !empty($draw_date) ? $draw_date : ""; ?>';
</script>
<script src="/w/js/vendor/jquery.mobile.custom.min.js"></script>
<script src="/w/js/main.js"></script>
