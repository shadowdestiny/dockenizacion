<!--[if lt IE 9]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<script>
$(document).bind("mobileinit", function(){
    $.mobile.ajaxEnabled=false;
    $.mobile.pushStateEnabled=false; // Disable Ajax
    $.mobile.ignoreContentEnabled=true; // Unable disactivation of Jquery behaviour on anchors, by adding on the parent/container of the links  data-ajax="false"
});
</script>


<script src="/w/js/vendor/jquery.mobile.custom.min.js"></script>
<script src="/w/js/vendor/picturefill.min.js" async></script>

{#
<script>
$(function(){
        alert('test')

    try{
        alert('test1')

     //   document.createEvent('TouchEvent');       
        //document.write('<script src="/w/js/vendor/fastclick.min.js"><\/script>');
        var script_fast = document.createElement('script');
        script_fast.src = "/w/js/vendor/fastclick.min.js";
        document.getElementsByTagName('head')[0].appendChild(script_fast);        
        var attachFastClick = Origami.fastclick;
        attachFastClick(document.body); // It removes the delay of 300ms on mobile browsers because of double tap
    }catch(e){
        alert(e)
        //empty
    }
});
</script>
#}
<script>
    var remain_time = '<?php echo $time_to_remain_draw; ?>';
    var time_out_closing_modal = '<?php echo !empty($timeout_to_closing_modal) ? $timeout_to_closing_modal : "";?>';
    var minutes_to_close = '<?php echo !empty($minutes_to_close) ? (int) $minutes_to_close : "";?>';
    var last_minute = '<?php echo $last_minute; ?>';
    var draw_date = '<?php echo !empty($draw_date) ? $draw_date : ""; ?>';

</script>

<script src="/w/js/vendor/svg4everybody.min.js"></script>
<script>$(function(){svg4everybody()})</script> {# SVG rendering for include svg for IE9+ #}

<script src="/w/js/vendor/jquery.countdown.min.js"></script>
<script src="/w/js/vendor/jquery.cookie.js"></script>
<script src="/w/js/vendor/easyModal.min.js"></script>


