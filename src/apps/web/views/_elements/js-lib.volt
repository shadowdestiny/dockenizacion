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
<script>
$(function(){
    try{
        document.createEvent('TouchEvent');
        document.write('<script src="/w/js/vendor/fastclick.min.js"><\/script>');
        var attachFastClick = Origami.fastclick;
        attachFastClick(document.body); // It removes the delay of 300ms on mobile browsers because of double tap
    }catch(e){
        //empty
    }
});
</script>


<script src="/w/js/vendor/svg4everybody.min.js"></script>
<script>$(function(){svg4everybody()})</script> {# SVG rendering for include svg for IE9+ #}

<script src="/w/js/vendor/jquery.countdown.min.js"></script>
<script src="/w/js/vendor/jquery.cookie.js"></script>
<script src="/w/js/vendor/easyModal.min.js"></script>
<script src="/w/js/main.js"></script>