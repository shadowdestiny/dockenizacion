(function($) {
     $.fn.tipr = function(options){
          var set = $.extend({
               'speed' : 200,
               'mode'  : 'bottom',
               'styled': 'normal'       
          }, options);

          return this.each(function(){
               var tipr_cont = '.tipr_container_' + set.mode;

               var pos = 0;
               $(this).on('mouseenter vclick',function(event){
                    event.stopPropagation();
                    event.preventDefault();
                    var d_m = set.mode;

                    if ($(this).attr('data-mode')){
                         d_m = $(this).attr('data-mode')
                         tipr_cont = '.tipr_container_' + d_m;
                    }
                    if($(tipr_cont).length) {
                         $(tipr_cont).remove();
                    }

                    var out = '<div class="tipr tipr_container_' + d_m + '"><div class="tipr_point_' + d_m + '"><div class="tipr_content">' + $(this).attr('data-tip') + '</div></div></div>';

                    if($(this).children().length > 1){
                         //
                    }else{
                         $(this).append(out);
                         pos = 1;
                    }

                    if(set.styled == "small"){
                         $(".tipr-small .tipr").css({"width":"130px"});
                    }

                    var w_t = $(tipr_cont).outerWidth();
                    var w_e = $(this).width();
                    var m_l = (w_e / 2) - (w_t / 2);
                    var myHeight=$(tipr_cont).height();
                    if(d_m == "top"){
                         $(tipr_cont).css({"margin-top": -myHeight+"px", "top":"-5px"});
                    }else if(d_m == "bottom"){
                         $(tipr_cont).css({"margin-top": myHeight+"px", "top":"-22px"});
                    }
                    $(tipr_cont).css({'margin-left': m_l + 'px'});
                    $(this).removeAttr('title alt');
                    $(tipr_cont).fadeIn(set.speed);
               });
               $(this).on('mouseleave',function(e){
                    $(tipr_cont).remove();
               });
               $(document).on('vclick', function(e){
                    if(pos == 1){
                         $(tipr_cont).remove();
                    }
                    pos = 0;
               });

          });
     };
     
})(jQuery);
