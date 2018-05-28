/* Initialise variables */

var globalFunctions = {
    setCurrency: function (value) {
        $.ajax({
            url: '/ajax/user-settings/setCurrency/' + value,
            type: 'GET',
            dataType: "json",
            success: function (json) {
                if (json.result = 'OK') {
                    location.href = location.href.split('#')[0];
                }
            },
            error: function (xhr, status, errorThrown) {
                alert("Sorry, there was a problem!");
                console.log("Error: " + errorThrown);
                console.log("Status: " + status);
                console.dir(xhr);
            },
        });
    },
    setLanguage: function (value) {
        $.ajax({
            url: '/ajax/user-settings/setLanguage/' + value,
            type: 'GET',
            dataType: "json",
            success: function (json) {
                if (json.result = 'OK') {
                    if (json.url === 0) {
                        location.href = location.href.split('#')[0];
                    } else {
                        location.href = json.url;
                    }


                }
            },
            error: function (xhr, status, errorThrown) {
                alert("Sorry, there was a problem!");
                console.log("Error: " + errorThrown);
                console.log("Status: " + status);
                console.dir(xhr);
            },
        });
    },
    playCart: function (params) {
        $.ajax({
            url: '/ajax/play-temporarily/temporarilyCart/',
            data: params,
            type: 'POST',
            dataType: "json",
            success: function (json) {
                if (json.result = 'OK') {
                    location.href = json.url;
                }
            },
            error: function (xhr, status, errorThrown) {
                //EMTD manage errrors
            },
        });
    }
};

var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
(function () {
  var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
  s1.async = true;
  s1.src = 'https://embed.tawk.to/591c2e4c64f23d19a89b2788/default';
  s1.charset = 'UTF-8';
  s1.setAttribute('crossorigin', '*');
  s0.parentNode.insertBefore(s1, s0);
})();

function btnShowHide(button, show, hide) {
  $(button).click(function () {
    $(show).show();
    $(hide).hide();
  });
}

function selectFix() { // Style the "Select"
  if ('querySelector' in document && 'addEventListener' in window) {
    // check query selector is recognised by the browser IE9+

    var obj = $('.mySelect');
    /*
     if(typeof $(obj).attr("disabled") == "undefined" || $(obj).attr("disabled") == "disabled"){
     console.log("test")
     $(this).parent().addClass("disabled");
     }
     */
    $('.mySelect option:selected').each(function (k) {
      var content = $(this).text();
      $('.select-txt').each(function (index, el) {
        if (index == k) {
          $(this).text(content);
        }
      });

      //elem.text(content);
    });
    $('.mySelect').each(function (k) {
      $(this).on('change', function () {
        var content = $('option:selected', this).text();
        $('.select-txt').each(function (index, el) {
          if (index == k) {
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
                    finish_text, finish_action) {
  return element.countdown(date).on('update.countdown', function (event) {
    if (event.offset.days == 0) {
      $(this).html(event.strftime(html_formatted_offset[0]));
    }
    if (event.offset.hours == 0) {
      $(this).html(event.strftime(html_formatted_offset[1]));
    }
    if (event.offset.minutes == 0) {
      $(this).html(event.strftime(html_formatted_offset[2]));
    }
    if (event.offset.days > 0) {
      $(this).html(event.strftime(html_formatted));
    }
  }).on('finish.countdown', function (event) {
    $(this).html(finish_text).parent().addClass('disabled');
    $(".box-estimated .content").removeAttr("href");
    finish_action();
  });
  //visit: http://hilios.github.io/jQuery.countdown to formatted html result
}

var varSize = 0
function checkSize() {
  if ($(".media").width() == "1") {         // max-width: 1200px
    varSize = 1;
  } else if ($(".media").width() == "2") {   // max-width: 992px)
    varSize = 2;
  } else if ($(".media").width() == "3") {   // max-width: 768px
    varSize = 3;
  } else if ($(".media").width() == "4") {   // max-width: 480px
    varSize = 4;
  } else if ($(".media").width() == "5") {   // max-width: 320px
    varSize = 5;
  }
  return varSize;
}

function menu(id, target) {
  $(id).hover(function (event) {
    $(target).show();
  }, function () {
    $(target).hide();
  });
}

function navCurrency() {
  if (varSize < 3) {
    menu(".li-currency", ".div-currency");
  }
}

function navLanguage() {
  if (varSize < 3) {
    menu(".li-language", ".div-language");
  }
}

$(function () {
  if (show_modal == 1) {
    $("#win").easyModal({
      top: 100,
      autoOpen: true,
      overlayOpacity: 0.7,
      overlayColor: "#000",
      transitionIn: 'animated fadeIn',
      transitionOut: 'animated fadeOut'
    });
  }

  selectFix();
  try {
    document.createEvent('TouchEvent');
    var script = document.createElement('script');
    script.src = "/w/js/vendor/fastclick.min.js";
    document.getElementsByTagName('head')[0].appendChild(script);
    //var attachFastClick = Origami.fastclick;
    FastClick.attach(document.body); // It removes the delay of 300ms on mobile browsers because of double tap
    //attachFastClick(document.body);
  } catch (e) {
  }

  $(".menu-ham").click(function () {
    //$(this).toggleClass('expanded').siblings('ul').slideToggle().toggleClass('open');
    $(this).toggleClass('expanded');
    $('.top-nav--mobile-account--menu').stop().toggle();
  });


  $('.top-nav--mobile-account--menu--close').click(function () {
    $(".menu-ham").toggleClass('expanded');
    $('.top-nav--mobile-account--menu').stop().toggle();
  });


  var first_page = (new Date().valueOf() - $.cookie('lastSeen') > 0);
  if ($.cookie('EM-law') && first_page) { //First time visitor, load cookies
    $('.box-cookies').remove();
  }
  if (!$.cookie('lastSeen')) {
    $.cookie('lastSeen', new Date().valueOf());
  }

  checkSize();
  $(window).resize(checkSize);

  navCurrency();
  $(window).resize(navCurrency);

  navLanguage();
  $(window).resize(navLanguage());

  /* Hide Currency after tapping on mobile */
  $('html').on('touchstart', function (e) {
    if ($('.div-currency').is(":visible")) {
      $('.div-currency').hide();
    } else if ($('.div-language').is(":visible")) {
      $('.div-language').hide();
    } else if (e.target.className.split(" ")[1] == "myCur") {
      $('.div-currency').show();
    } else if (e.target.className.split(" ")[1] == "myLang") {
      $('.div-language').show();
    }
  });

  $(".div-currency").on('touchstart', function (e) {
    e.stopPropagation();
  });

  $(".div-language").on('touchstart', function (e) {
    e.stopPropagation();
  });

  $('#funds-value').on('keyup', function (e) {
    var regex = /^\d+(\.\d{0,2})?$/g;
    var value = e.target.value;
    show_fee_text(value);
  });

  $('#funds-value,#card-cvv,#card-number').on('keypress', function (e) {

    var pattern = /^[0-9\.]+$/;
    if (e.target.id == 'card-cvv' || e.target.id == 'card-number') {
      pattern = /^[0-9]+$/;
    }
    var codeFF = e.keyCode;
    var code = e.which
    var chr = String.fromCharCode(code);
    if (codeFF == 8 || codeFF == 37 || codeFF == 38 || codeFF == 39 || codeFF == 40) {
      return true;
    }
    if (!pattern.test(chr)) {
      e.preventDefault();
    }
  });

});


//v2
$(document).ready(function () {

  // FAQ accordion function

  if ($('.faq-section .answer').length) {
    $('.faq-section .accordion-block-outer').find('h2').click(function () {
      $(this).parent().toggleClass('expanded');
      $(this).parent().find('.accordion-block-outer--contet').stop().slideToggle();
    });
  }

  if ($('.faq-section .answer').length) {
    $('.faq-section .answer').find('h3').click(function () {
      $(this).parent().toggleClass('expanded');
      $(this).parent().find('p').stop().slideToggle();
    });
  }

  //Transactions page accordion
  if ($('#content.account-page .table_transactions_v2 tbody tr td.amount p').length) {
    $('#content.account-page .table_transactions_v2 tbody tr td.amount p').click(function () {
      $(this).parent().parent().next().toggleClass('expanded');
    });
  }

  //Footer box links accordion mobile
  if ($('.main-foot .box-links').length) {
    $('.main-foot .box-links').find('strong').click(function () {
      $(this).parent().toggleClass('expanded');
      $(this).parent().find('ul').stop().slideToggle();
    });
  }

  //Block accordion mobile
  if ($('.block--text--accordion').length) {

      $('.block--text--accordion h2, .block--text--accordion h3').click(function () {
        var mobile = 768;
        if (document.documentElement.clientWidth < mobile ) {
          console.log(document.documentElement.clientWidth);
          $(this).parent().toggleClass('expanded');
          $(this).next().stop().slideToggle();
        }
      });

  }

  //Top nav mobile account menu
  //if ($('.top-nav--mobile-account--icon').length) {
  //  $('.top-nav--mobile-account--icon, .top-nav--mobile-account--menu--close').click(function () {
  //    $('.top-nav--mobile-account--menu').stop().toggle();
  //  });
  //}

  //Shopping cart accordion
  if ($('.action--arrow--mobile').length) {
    $('.action--arrow--mobile').click(function () {
      $(this).toggleClass('expanded').closest('tr').find('.td--content--accordion').each(function(){
        $(this).stop().toggle();
      });
    });
  }


  //Mobile menu language accordion
  if ($('.top-nav--mobile-account--menu--list .li--lang').length) {
    $('.top-nav--mobile-account--menu--list .li--lang').click(function () {
        $('.li--lang--languages').stop().slideToggle();
    });
  }
  //Mobile menu language accordion
  if ($('.top-nav--mobile-account--menu--list .li--cur').length) {
    $('.top-nav--mobile-account--menu--list .li--cur').click(function () {
        $('.li--cur--currencies').stop().slideToggle();
    });
  }

  //Mobile dashboard menu
  if ($('.dashboard-menu--balance--link').length) {
    $('.dashboard-menu--balance--link').click(function () {
        $('.dashboard-menu--mobile').stop().hide();
        $('.dashboard-menu--mobile--account').stop().hide();
        $('.dashboard-menu--mobile--logout').stop().hide();
        $('.dashboard-menu--mobile--back ').stop().show();
        $('.dashboard-menu--mobile--back').stop().show();
        $('.dashboard-menu--mobile--content').stop().show();
    });

    $('.dashboard-menu--mobile--back.dashboard-menu--mobile--back--balance').click(function () {
        $('.dashboard-menu--mobile--back ').stop().hide();
        $('.dashboard-menu--mobile--back').stop().hide();
        $('.dashboard-menu--mobile--content').stop().hide();
        $('.dashboard-menu--mobile').stop().show();
        $('.dashboard-menu--mobile--account').stop().show();
        $('.dashboard-menu--mobile--logout').stop().show();
    });
  }

  // Tickets tables mobile
  if($('.tickets-table').length){

    $(this).find('.date-from').click(function(){
      $(this).parent().toggleClass('opened');
    });


    // Tickets tables transitions
    $('.dashboard-menu--my-subscriptions').click(function(){
      var table = $(this).attr('data-role');
      $('.nav--tickets--mobile').addClass('hidden');
      $('.dashboard-menu--mobile--back--tickets').hide();
      $('.'+table+'').show();
    });

    $('.dashboard-menu--mobile--back--submenu').click(function(){
      $(this).closest('.tickets-blocker').hide();
      $('.dashboard-menu--mobile--back--tickets').show();
      $('.nav--tickets--mobile').removeClass('hidden');
    });


  }


  if($('#show-more-results').length){

    $('#history-numbers tr:lt(11)').removeClass('more');

    $('.history-numbers-block').on('click', '#show-more-results', function(){

      $('#history-numbers tr.more:lt(10)').removeClass('more');

      if($('#history-numbers tr.more').length){

      } else {
        $('#show-more-results').fadeOut(700);
      }

    });
  }

  if($('.powerball--result-page--content .see-results-block').length){
    $('.powerball--result-page--content .see-results-block').click(function(){

      setTimeout(function() {
        if($('.powerball--result-page--content .see-results-block').find('label').hasClass('ui-checkbox-on')){
          $('.powerball--result-page--content').addClass('see-more--on');
        } else {
          $('.powerball--result-page--content').removeClass('see-more--on');
        }
      }, 500);

    });
  }

});

//*************** Font resize Start ***************************
(function($) {
    $.fn.fontSizeTuner = function(options) {
        var $this = $(this);
        
        var resize = function($elem) {
            var parentWidth  = $elem.parent().width();
            var parentHeight = $elem.parent().height();
            var origFontSize = parseInt($elem.data('original-font-size'));
            var origPosition = $elem.data('original-position');
            var fontSize = origFontSize + 1;
            
            $elem.css('position', 'absolute');
            
            do {
                fontSize --;
                $elem.css('font-size', fontSize + 'px');
                var elemWidth  = $elem.width();
                var elemHeight = $elem.height();
            } while ((parentWidth < elemWidth || parentHeight < elemHeight) && fontSize > 3);
            
            $elem.css({
                position : origPosition,
                opacity : 1,
            });
        }
        
        $this.each(function() {
            if (!$(this).data('original-font-size')) {
                var style = window.getComputedStyle(this);
                $(this)
                    .data('original-font-size', style.fontSize)
                    .data('original-position', style.position);
            }
            resize($(this));
        });
        
        var onResize = function() {
           $this.each(function() {
               resize($(this));
           })
        };
        
        $(window).resize(onResize);
 
      return this;
    }
})(jQuery);

(function($) {
    $('.resizeme').fontSizeTuner();
}(jQuery));

//*************** Font resize End ***************************