<script src="/w/js/vendor/jquery-1.11.3.min.js"></script>
<script src="/w/js/vendor/svgxuse.min.js"></script> {# Render SVG for IE and problematic browsers #} 
<script src="/w/js/vendor/jquery.cookie.min.js"></script>
<script src="/w/js/vendor/jquery.countdown.min.js"></script>
<script src="/w/js/vendor/picturefill.min.js"></script>
<script src="/w/js/vendor/easyModal.min.js"></script>
<script src="/w/js/vendor/accounting.min.js"></script>
<script src="/w/js/vendor/jquery.lazyloadxt.min.js"></script>
<script src="/w/js/vendor/jquery.lazyloadxt.widget.js"></script>
{%  if ga_code is defined %}
<script src="/w/js/vendor/ganalytics.min.js"></script>
{% endif %}


<script>
$(document).bind("mobileinit", function(){
    $.mobile.ajaxEnabled=false;
    $.mobile.pushStateEnabled=false; // Disable Ajax
    $.mobile.ignoreContentEnabled=true; // Unable disactivation of Jquery behaviour on anchors, by adding on the parent/container of the links  data-ajax="false"
});

{# DO NOT DELETE THIS, compressed version below /* Browser detection by feature detection --> http://stackoverflow.com/questions/9847580/how-to-detect-safari-chrome-ie-firefox-and-opera-browser 
var isOpera = (!!window.opr && !!opr.addons) || !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0; // Opera 8.0+
var isFirefox = typeof InstallTrigger !== 'undefined'; // Firefox 1.0+
var isSafari = Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0; // At least Safari 3+: "[object HTMLElementConstructor]"
var isIE = /*@cc_on!@*/false || !!document.documentMode; /* Internet Explorer 6-11
var isEdge = !isIE && !!window.StyleMedia;  // Edge 20+
var isChrome = !!window.chrome && !!window.chrome.webstore; // Chrome 1+
var isBlink = (isChrome || isOpera) && !!window.CSS; // Blink engine detection
*/ #}

var isOpera=!!window.opr&&!!opr.addons||!!window.opera||navigator.userAgent.indexOf(" OPR/")>=0,isFirefox="undefined"!=typeof InstallTrigger,isSafari=Object.prototype.toString.call(window.HTMLElement).indexOf("Constructor")>0,isIE=!!document.documentMode,isEdge=!isIE&&!!window.StyleMedia,isChrome=!!window.chrome&&!!window.chrome.webstore,isBlink=(isChrome||isOpera)&&!!window.CSS;

{# /* DRAW TIME */ #}
var remain_time = '<?php echo empty($time_to_remain_draw) ? 0 : 1; ?>';
var time_out_closing_modal = '<?php echo !empty($timeout_to_closing_modal) ? $timeout_to_closing_modal : "";?>';
var minutes_to_close = '<?php echo !empty($minutes_to_close) ? (int) $minutes_to_close : "";?>';
var minutes_to_close_rounded = '<?php echo isset($minutes_to_close_rounded) ? (int) $minutes_to_close_rounded : 0;?>';
var last_minute = '<?php echo isset($last_minute) ?  $last_minute : ""; ?>';
var draw_date = '<?php echo !empty($draw_date) ? $draw_date : ""; ?>';
var show_modal = '<?php echo !empty($show_modal_winning) ? 1 : 0; ?>';
</script>
<script src="/w/js/vendor/jquery.mobile.custom.min.js"></script>
<script src="/w/js/main.js"></script>
