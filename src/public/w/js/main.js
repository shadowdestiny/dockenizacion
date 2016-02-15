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
        remain_time = false;
    }

    $('.ending').hide();
    var now_date = new Date().getMinutes();
    var draw_date_minutes = new Date(draw_date).getMinutes();
    draw_date_minutes = draw_date_minutes == 0 ? 60 : draw_date_minutes;   
    var minutes_value =  draw_date_minutes - now_date;
    if(draw_date_minutes < now_date) {
        now_date = 60 - now_date;
        minutes_value = draw_date_minutes + now_date;
    }
    var interval_warning_close = null;
   // var is_remain_time = remain_time == "" ? false : remain_time;
    var fade_value = 800;
    var interval_warning = 300000;
    if(remain_time && minutes_value > 1){
        if(minutes_value > 5){
            $('.ending').text('The draw will close in ' + minutes_to_close + ' minutes')
        }else{
            interval_warning = 30000;
            $('.ending').text('The draw will close in ' + minutes_value + ' minutes')
        }
        $('.ending').fadeIn(fade_value);
        setTimeout(function(){
            $('.ending').fadeOut(fade_value);
        },30000);

        interval_warning_close = setInterval(function(){
            minutes_value =  getMinutes();
            if(minutes_value > 5) {
                minutes_to_close = minutes_to_close - 5;
                $('.ending').text('The draw will close in '+ minutes_to_close +' minutes');
                $('.ending').fadeIn();
                setTimeout(function(){
                    if(getMinutes() < 1 ) {
                        finish_countdown_warning_close_draw(interval_warning_close);
                    }
                    $('.ending').fadeOut(fade_value);
                },3000);
            }else if(minutes_value > 1){
                $('.ending').text('The draw will close in '+ minutes_value +' minutes');
                $('.ending').fadeIn();
                console.log('pasa2');
                setTimeout(function(){
                    if(getMinutes() < 1 ) {
                        console.log('pasa5');
                        finish_countdown_warning_close_draw(interval_warning_close);
                    }
                    $('.ending').fadeOut();
                },3000);
                interval_warning_close = setInterval(interval_warning_close, 45000);
            }else{
                console.log('pasa3');
                finish_countdown_warning_close_draw(interval_warning_close);
            }
        },interval_warning);
    }

    var is_last_minute = typeof last_minute == 'undefined' ? false : last_minute;
    if(is_last_minute){
        finish_countdown_warning_close_draw(interval_warning_close);
    }

    function getMinutes(){
        now_date = new Date().getMinutes();
        draw_date_minutes = (new Date(draw_date).getMinutes() == 0) ? 60 : new Date(draw_date).getMinutes();
        return draw_date_minutes - now_date;
    }

    checkSize();
    $(window).resize(checkSize);
});
