/* Initialise variables */

var varSize = 0
var mobile = 0;
var navUrl = [];
var globalFunctions = {
    setCurrency : function (value) {
        $.ajax({
            url: '/ajax/user-settings/setCurrency/'+value,
            type: 'GET',
            dataType: "json",
            success: function(json) {
                if(json.result = 'OK') {
                    location.href = location.href.split('#')[0];
                }
            },
            error: function (xhr, status, errorThrown) {
                alert( "Sorry, there was a problem!" );
                console.log( "Error: " + errorThrown );
                console.log( "Status: " + status );
                console.dir( xhr );
            },
        });
    },
    playCart : function (params) {
        $.ajax({
            url: '/ajax/play-temporarily/temporarilyCart/',
            data: params,
            type: 'POST',
            dataType: "json",
            success: function(json) {
                if(json.result = 'OK') {
                    location.href = json.url;
                }
            },
            error: function (xhr, status, errorThrown) {
                //EMTD manage errrors
            },
        });
    }
};


function checkSize(){
	if($(".media").width() == "1"){ 		// max-width: 1200px
		varSize = 1;
	}else if($(".media").width() == "2"){ 	// max-width: 992px)
		varSize = 2;
	}else if($(".media").width() == "3"){	// max-width: 768px
		varSize = 3;
	}else if($(".media").width() == "4"){	// max-width: 480px
		varSize = 4;
	}else if($(".media").width() == "5"){	// max-width: 320px
		varSize = 5;
	}

	if(varSize >= 3){
		// MOBILE
		$(".li-currency").unbind('mouseenter mouseleave');

		if(mobile == 0){ // store the original url
			navUrl[0] = $(".your-account").attr("href");
			navUrl[1] = $(".li-currency .link").attr("href");
		}
		// add sliding capability
		$(".your-account").attr({"data-transition":"slide", "href":"#nav-account"});
		$(".li-currency .link").attr({"data-transition":"slide", "href":"#language"});
		mobile = 1; // You have a mobile size website
	}else{
		// DESKTOP
		menu(".li-currency", ".div-currency");
		if(mobile == 1){ // You came from a screen sized mobile interface
			$(".your-account").attr({"data-transition":"slide", "href":navUrl[0]});
			$(".li-currency .link").attr({"data-transition":"slide", "href":navUrl[1]});
		}
	    $(".main-nav .li-your-account").hover(function(event){
	    	$(".main-nav .subnav").show();
	    }, function(){
			$(".main-nav .subnav").hide();
	    });
		mobile = 0;
	}

	return varSize;
}

function menu(id, target){
	$(id).hover(function(event){
		$(target).show();
	}, function(){
		$(target).hide();
	});
}

function btnShowHide(button, show, hide){
	$(button).click(function(){
		$(show).show();
		$(hide).hide();
	});
}

function selectFix(){ // Style the "Select"
    if('querySelector' in document && 'addEventListener' in window){
        // check query selector is recognised by the browser IE9+
        var value;
        var obj = $('.mySelect');
/*
        if(typeof $(obj).attr("disabled") == "undefined" || $(obj).attr("disabled") == "disabled"){
            console.log("test")
            $(this).parent().addClass("disabled");
        }
*/
        $('.mySelect option:selected').each(function(k){    
            var content = $(this).text();            
            $('.select-txt').each(function(index, el) {
                if(index == k) {
                    $(this).text(content);
                }
            });
            
            //elem.text(content);            
        });        
        $('.mySelect').each(function(k){
            $(this).on('change',function(){
                var content = $('option:selected',this).text();
                $('.select-txt').each(function(index, el) {
                    if(index == k) {                        
                        $(this).text(content);
                    }
                });
            })
        })
    }
}

function count_down(element,
                    html_formatted,
                    html_formatted_offset,
                    date,
                    finish_text,finish_action) {

    return element.countdown(date).
        on('update.countdown', function(event){
            if(event.offset.days == 0) {
                $(this).html(event.strftime(html_formatted_offset[0]));
            }
            if(event.offset.hours == 0){
                $(this).html(event.strftime(html_formatted_offset[1]));
            }
            if(event.offset.minutes == 0){
                $(this).html(event.strftime(html_formatted_offset[2]));
            }
            if(event.offset.days > 0) {
                $(this).html(event.strftime(html_formatted));
            }
        }).on('finish.countdown',function(event){
            $(this).html(finish_text).parent().addClass('disabled');
            $(".box-estimated .content").removeAttr("href");
            finish_action();
        });
     //visit: http://hilios.github.io/jQuery.countdown to formatted html result
}
$(function(){
	checkSize();
	$(window).resize(checkSize);

    selectFix();
    try{
        document.createEvent('TouchEvent');
        var script = document.createElement('script');
        script.src = "/w/js/vendor/fastclick.min.js";
        document.getElementsByTagName('head')[0].appendChild(script);
        //var attachFastClick = Origami.fastclick;
        FastClick.attach(document.body); // It removes the delay of 300ms on mobile browsers because of double tap
        //attachFastClick(document.body);
    }catch(e){

    }

    $(".menu-ham").click(function(){
        $(this).toggleClass('expanded').siblings('ul').slideToggle().toggleClass('open');
    });

    var first_page = (new Date().valueOf() - $.cookie('lastSeen') > 0);
    if($.cookie('EM-law') && first_page){ //First time visitor, load cookies
        $('.box-cookies').remove();
    }
    if(!$.cookie('lastSeen')) {
        $.cookie('lastSeen', new Date().valueOf());
    }
});

$(function(){

    $('.ending').hide();
    if( remain_time ) {
        $('.ending').show();
        setTimeout(function(){
            $('.ending').hide();
        },30000);
        setTimeout(function(){
            setInterval(function(){
                $('.ending').show();
                setTimeout(function(){
                    $('.ending').hide();
                },30000);
            },60000);
        },60000);
    }
});



