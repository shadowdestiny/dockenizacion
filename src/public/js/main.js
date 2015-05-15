function arrowBox(id, myCase){
	var obj = $(id);
	var width = obj.outerWidth();
	var sibling = obj.siblings(".link");
	var widthSib = sibling.outerWidth();
	var padSib = sibling.outerWidth() - sibling.width();
	var widthIco = 8;
	switch(myCase){
		case 1: // International arrow menu stop to be on the edge
			obj.css({"margin-left": widthSib-width/2-padSib/2-widthIco/2-145});
			break; 
		default:
			obj.css({"margin-left": widthSib-width/2-padSib/2-widthIco/2});
	}
}

function checkSize(){
//	console.log("test= "+$(".media").width())
	if($(".media").width() == "1px"){
//		console.log("media = 1") // max-width: 1200px
	}else if($(".media").width() == "2px"){
//		console.log("media = 2") // max-width: 992px)
	}else if($(".media").width() == "3px"){
//		console.log("media = 3") // max-width: 768px
	}else if($(".media").width() == "4px"){
//		console.log("media = 4") // max-width: 480px
	}else if($(".media").width() == "5px"){
//		console.log("media = 5") // max-width: 320px
	}
}

function activateSub(id, target, action){
	var obj = $(id);
	var myTar = $(target);
	var myAction = $(action);

	$(document).click(function(event){
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
	});
}

$(function(){
	checkSize();
	 $(window).resize(checkSize);

	activateSub(".help", ".sub-help", ".sub-help a");
	activateSub(".inter", ".sub-inter", ".sub-inter .btn");

	arrowBox(".sub-help");
	arrowBox(".sub-inter", 1);
});
