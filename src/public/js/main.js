/* Initialise variables */
var varSize = 0
var elements = [ // id, target, action, event
	[".help", ".sub-help", ".sub-help a"],
	[".inter", ".sub-inter", ".sub-inter .btn"]
];

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
		elements.forEach(function (element){
			$(element[1]).hide();
		});
	}

//	console.log("varSize= "+varSize)
	return varSize;
}

function arrowBox(id, myCase){
	var obj = $(id);
	var width = obj.outerWidth();
	var sibling = obj.siblings(".link");
	var widthSib = sibling.outerWidth();
	var padSib = sibling.outerWidth() - sibling.width();
	var widthIco = 8;
	switch(myCase){
		//EMTD I don't like that 161px, or 16px manually wroted. I want seme formula more dynamic to adjust the pointer, because on mobile doesn't center properly.
		case 1: // International arrow menu stop to be on the edge
			obj.css({"margin-left": widthSib-width/2-padSib/2-widthIco/2-161}); 
			break; 
		default:
			obj.css({"margin-left": widthSib-width/2-padSib/2-widthIco/2-16});
	}
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


$(function(){
	checkSize();
	$(window).resize(checkSize);

	arrowBox(".sub-help");
	arrowBox(".sub-inter", 1);

	$('.menu-ham').click(function(){
		$(this).toggleClass('expanded').siblings('ul').slideToggle().toggleClass('open');
	});


	$(document).click(function(event){
		if(varSize < 3){
			elements.forEach(function (element){
				activateSub(element[0], element[1], element[2], event);
			}); 
		}
	});

});
