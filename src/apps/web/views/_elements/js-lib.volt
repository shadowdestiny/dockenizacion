<!--[if lt IE 9]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<script src="w/js/vendor/jquery-1.11.3.min.js"></script>
<script>
$(document).bind("mobileinit", function(){
    $.mobile.ajaxEnabled=false;
    $.mobile.pushStateEnabled=false; // Disable Ajax
    $.mobile.ignoreContentEnabled=true; // Unable disactivation of Jquery behaviour on anchors, by adding on the parent/container of the links  data-ajax="false"
});
</script>
<script src="w/js/vendor/jquery.mobile.custom.min.js"></script>
<script src="w/js/vendor/picturefill.min.js" async></script>
<script src="w/js/vendor/fastclick.min.js"></script>

{#
<script src="w/js/lazysizes.min.js"></script>
#}
<script src="w/js/vendor/jquery.countdown.min.js"></script>
<script src="w/js/main.js"></script>


