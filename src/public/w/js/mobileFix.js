var mobile = 0;
var navUrl = [];

function navValue(){
    if(varSize >= 3){
        // MOBILE
        $(".li-currency").unbind('mouseenter mouseleave');
        $(".li-language").unbind('mouseenter mouseleave');
        if(myLogged){
            if(mobile == 0){ // store the original url
                navUrl[0] = $(".your-account").attr("href");
                navUrl[1] = $(".li-currency .link").attr("href");
                navUrl[2] = $(".li-language .link").attr("href");
            }
            // add sliding capability
            $(".your-account").attr({"data-transition":"slide", "href":"#nav-account"});
        }
        $(".li-currency .link").attr({"data-transition":"slide", "href":"#language"});
        $(".li-language .link").attr({"data-transition":"slide", "href":"#language"});
        mobile = 1; // You have a mobile size website
    }else{
        // DESKTOP
        if(myLogged){
            if(mobile == 1){ // You came from a screen sized mobile interface
                $(".your-account").attr({"data-transition":"slide", "href":navUrl[0]});
                $(".li-currency .link").attr({"data-transition":"slide", "href":navUrl[1]});
                $(".li-language .link").attr({"data-transition":"slide", "href":navUrl[2]});
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