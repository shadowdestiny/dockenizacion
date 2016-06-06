$(document).bind("mobileinit", function(){
    $.mobile.ajaxEnabled=false;
    $.mobile.pushStateEnabled=false; // Disable Ajax
    $.mobile.ignoreContentEnabled=true; // Unable disactivation of Jquery behaviour on anchors, by adding on the parent/container of the links  data-ajax="false"
});

var isOpera=!!window.opr&&!!opr.addons||!!window.opera||navigator.userAgent.indexOf(" OPR/")>=0,isFirefox="undefined"!=typeof InstallTrigger,isSafari=Object.prototype.toString.call(window.HTMLElement).indexOf("Constructor")>0,isIE=!!document.documentMode,isEdge=!isIE&&!!window.StyleMedia,isChrome=!!window.chrome&&!!window.chrome.webstore,isBlink=(isChrome||isOpera)&&!!window.CSS;

/* DRAW TIME */
var remain_time = '<?php echo empty($time_to_remain_draw) ? 0 : 1; ?>';
var time_out_closing_modal = '<?php echo !empty($timeout_to_closing_modal) ? $timeout_to_closing_modal : "";?>';
var minutes_to_close = '<?php echo !empty($minutes_to_close) ? (int) $minutes_to_close : "";?>';
var minutes_to_close_rounded = '<?php echo isset($minutes_to_close_rounded) ? (int) $minutes_to_close_rounded : 0;?>';
var last_minute = '<?php echo isset($last_minute) ?  $last_minute : ""; ?>';
var draw_date = '<?php echo !empty($draw_date) ? $draw_date : ""; ?>';
var show_modal = '<?php echo !empty($show_modal_winning) ? 1 : 0; ?>';
