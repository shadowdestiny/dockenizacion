/* Initialise variables */
var varSize = 0
var elements = [ // id, target, action, event
	[".help", ".sub-help", ".sub-help a"],
	[".inter", ".sub-inter", ".sub-inter .btn"]
];
var mobile = 0;
var navUrl = [];

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

		elements.forEach(function (element){
			$(element[1]).hide();
		});

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

//	console.log("varSize= "+varSize)
	return varSize;
}

function activateSub(id, target, action, event){
	var obj = $(id);
	var myTar = $(target);
	var myAction = $(action);

	if($(event.target).closest(myAction).length > 0){	// Save buttons or links
		// Insert here the save action
		myTar.toggle();
		}else if($(event.target).closest(myTar).length > 0){// Pull down area
		//do nothing
	}else if($(event.target).closest(obj).length > 0){ 	// Menu link
		myTar.toggle();
	}else{												// Clicking everywhere else
		if(myTar.is(":visible")){ 
			myTar.hide();
		}
	}
	event.stopPropagation();
}

function menu(id, target){
	$(id).hover(function(event){
		$(target).show();
	}, function(){
		$(target).hide();
	});
}


$(function(){
	checkSize();
	$(window).resize(checkSize);

	$('.menu-ham').click(function(){
		$(this).toggleClass('expanded').siblings('ul').slideToggle().toggleClass('open');
	});


	$(document).click(function(event){
		if(varSize < 3){
			elements.forEach(function (element){
			//	activateSub(element[0], element[1], element[2], event);
			}); 
		}
	});

});
