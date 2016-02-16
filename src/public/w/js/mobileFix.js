var mobile = 0;
var navUrl = [];

function menu(id, target){
    $(id).hover(function(event){
        $(target).show();
    }, function(){
        $(target).hide();
    });
}

function navValue(){
    if(varSize >= 3){
        // MOBILE
        $(".li-currency").unbind('mouseenter mouseleave');
        if(myLogged){
            if(mobile == 0){ // store the original url
                navUrl[0] = $(".your-account").attr("href");
                navUrl[1] = $(".li-currency .link").attr("href");
            }
            // add sliding capability
            $(".your-account").attr({"data-transition":"slide", "href":"#nav-account"});
            $(".li-currency .link").attr({"data-transition":"slide", "href":"#language"});
        }

        mobile = 1; // You have a mobile size website
    }else{
        // DESKTOP
        menu(".li-currency", ".div-currency");
        if(myLogged){
            if(mobile == 1){ // You came from a screen sized mobile interface
                $(".your-account").attr({"data-transition":"slide", "href":navUrl[0]});
                $(".li-currency .link").attr({"data-transition":"slide", "href":navUrl[1]});
            }
            $(".main-nav .li-your-account").hover(function(event){
                $(".main-nav .subnav").show();
            }, function(){
                $(".main-nav .subnav").hide();
            });
        }
        mobile = 0;
    }
}
$(function(){
    navValue();
    $(window).resize(navValue);
});