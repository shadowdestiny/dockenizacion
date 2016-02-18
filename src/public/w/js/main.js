/* Initialise variables */

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

function btnShowHide(button, show, hide){
	$(button).click(function(){
		$(show).show();
		$(hide).hide();
	});
}

function selectFix(){ // Style the "Select"
    if('querySelector' in document && 'addEventListener' in window){
        // check query selector is recognised by the browser IE9+

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

var varSize = 0
function checkSize(){
    if($(".media").width() == "1"){         // max-width: 1200px
        varSize = 1;
    }else if($(".media").width() == "2"){   // max-width: 992px)
        varSize = 2;
    }else if($(".media").width() == "3"){   // max-width: 768px
        varSize = 3;
    }else if($(".media").width() == "4"){   // max-width: 480px
        varSize = 4;
    }else if($(".media").width() == "5"){   // max-width: 320px
        varSize = 5;
    }
    return varSize;
}


$(function(){
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
    if(!$.cookie('lastSeen')){
        $.cookie('lastSeen', new Date().valueOf());
    }

    var timeout_warning = '';
    var finish_countdown_warning_close_draw = function (interval_warning_close) {
        console.log('finish countdown');
        window.clearInterval(interval_warning_close);
        return $('.ending').countdown(draw_date).
        on('update.countdown', function (event) {
            $('.ending').fadeIn(fade_value);
            $(this).html("The draw will close in " +  event.strftime('%-Ss'));
        }).on('finish.countdown', function (event) {
            $(this).html('Today’s draw is closed, you will play for the next');
            setTimeout(function () {
                $('.ending').fadeOut(fade_value);
            }, 30000);
        });
    };

    if(typeof draw_date == 'undefined'){
        draw_date = new Date();
    }
    if(typeof remain_time == 'undefined'){
        remain_time = 1;
    }

    $('.ending').hide();
    var now_date = new Date().getMinutes();
    var draw_date_minutes = new Date(draw_date).getMinutes();
    var minutes_value =  minutes_to_close;
    var interval_warning_close = null;
    var first_load = true;
    var fade_value = 800;
    var interval_warning = 300000;
    var timeout_first_warning = 30000;
    if(remain_time == 1 && minutes_value >= 1 && minutes_value < 30){
        if (minutes_value > 1 && minutes_value <= 5){
            interval_warning = 30000;
        }else if (minutes_value == 1){
            interval_warning = 5000;
            timeout_first_warning = 10000;
        }
        $('.ending').text('The draw will close in ' + minutes_value + ' minutes')
        $('.ending').fadeIn(fade_value);
        setTimeout(function(){
            $('.ending').fadeOut(fade_value);
        },timeout_first_warning);
        interval_warning_close = setInterval(function(){
            minutes_value =  getMinutes();
            if(!first_load) {
                if(minutes_value > 6) {
                    var minutes_to_close = minutes_value - 5;
                    interval_warning_close = logic_warning_interval(minutes_to_close, finish_countdown_warning_close_draw, interval_warning_close, interval_warning);
                }else if(minutes_value > 1){
                    interval_warning = 35000;
                    if(minutes_value > 2 ) interval_warning = 60000;
                    interval_warning_close = logic_warning_interval(minutes_value, finish_countdown_warning_close_draw, interval_warning_close, interval_warning);
                }else if(minutes_value <= 1) {

                    finish_countdown_warning_close_draw(interval_warning_close);
                }
            }
            first_load = false;
        },interval_warning);
    }
    if(minutes_value < 1){
        finish_countdown_warning_close_draw(interval_warning_close);
    }


    function logic_warning_interval(minutes_value, finish_countdown_warning_close_draw, interval_warning_close,timeout_interval) {
        $('.ending').text('The draw will close in ' + minutes_value + ' minutes');
        $('.ending').fadeIn();
        setTimeout(function () {
            if (getMinutes() < 1) {
                finish_countdown_warning_close_draw(interval_warning_close);
            }
            $('.ending').fadeOut();
        }, 3000);
        interval_warning_close = setInterval(interval_warning_close, timeout_interval);
        return interval_warning_close;
    }


    function getMinutes(){
        now_date = new Date().getMinutes();
        draw_date_minutes = (new Date(draw_date).getMinutes() == 0) ? 60 : new Date(draw_date).getMinutes();
        return draw_date_minutes - now_date;
    }

    checkSize();
    $(window).resize(checkSize);

    /* Hide Currency after tapping on mobile */
    $('html').on('touchstart', function(e){
        if($('.div-currency').is(":visible")){
            $('.div-currency').hide();
        }else{
            if(e.target.className.split(" ")[1] == "myCur"){
                $('.div-currency').show();
            };
        }
    })
    $(".div-currency").on('touchstart',function(e){
        e.stopPropagation();
    });

});
