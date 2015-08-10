var numberCount = [0,0,0,0,0,0,0];
var starCount = numberCount.slice();
var totalCount = numberCount.slice();
var hasValue = [0,0,0,0,0,0,0];

function checkMark(arrayCount){
	obj = $(".num"+arrayCount+" .ico-checkmark")
	if(numberCount[arrayCount] >= 5 && starCount[arrayCount] >= 2){
		obj.show();
		hasValue[arrayCount] = 1;
	}else{
		hasValue[arrayCount] = 0;
		obj.hide();
	}

	if(numberCount[arrayCount] > 0 || starCount[arrayCount] > 0){
		$(".num"+arrayCount+" .line").addClass("number-on");
	}else{
		$(".num"+arrayCount+" .line").removeClass("number-on");
	}

	if(numberCount[arrayCount] == 0 && starCount[arrayCount] == 0){
		$(".fix-margin").hide();
	}else{
		$(".fix-margin").css("display","inline-block");
	}

	if(jQuery.inArray( 1, hasValue ) !== -1){ // check if there is a value selected
		$(".add-cart").addClass("active");
	}else{
		$(".add-cart").removeClass("active");
	}

//	console.log(arrayCount+" totalCount= "+totalCount[arrayCount]+" // numberCount= "+numberCount[arrayCount]+" // starCount= "+starCount[arrayCount]);
}

function playLine(button, type){
	$(button).click(function(){
		myThis = $(this).closest(".col2").attr('class').split(" "); 

		valNum = myThis[1].split("num");
		line = "."+myThis[1];

		// remove count if disabled
		if($(this).hasClass("active")){
			if(type == "number"){
				numberCount[valNum[1]]--
			}else if(type == "star"){
				starCount[valNum[1]]--
			}
			totalCount[valNum[1]]--
		}else{
			if(type == "number"){
				numberCount[valNum[1]]++
			}else if(type == "star"){
				starCount[valNum[1]]++
			}
			totalCount[valNum[1]]++
		}

		$(this).toggleClass('active');
		checkMark(valNum[1]);
		
	});
}

function setIntervalRepetition(callback, delay, repetitions){
    var x = 0;
    var intervalID = window.setInterval(function (){
       callback();
       if (++x === repetitions){
           window.clearInterval(intervalID);
       }
    }, delay);
}

function shuffle(array){ // Fisher–Yates shuffle
  var m = array.length, t, i;

  // While there remain elements to shuffle…
  while (m) {

    // Pick a remaining element…
    i = Math.floor(Math.random() * m--);

    // And swap it with the current element.
    t = array[m];
    array[m] = array[i];
    array[i] = t;
  }
  return array;
}


function randomCalculation(line, value){
	var arr = new Array(45);
	var arr2 = new Array(11);
	for(var i=0; i < arr.length; i++){arr[i] = i+1;}
	for(var i=0; i < arr2.length; i++){arr2[i] = i+1;}

	setIntervalRepetition(function(){
		shuffle(arr);
		shuffle(arr2);

		$(line+" .values .active").toggleClass('active');

		for(var i=0; i < 5; i++){
	 		$(line+" .n"+arr[i]).toggleClass('active');
		}
		for(var i=0; i < 2; i++){
	 		$(line+" .s"+arr2[i]).toggleClass('active');
		}

	}, 50, 12);

	numberCount[value] = 5;
	starCount[value] = 2;
	totalCount[value] = 7;
	checkMark(value);
}

function randomNum(button){
	$(button).click(function(){
		myThis = $(this).closest(".col2").attr('class').split(" "); 
		valNum = myThis[1].split("num");
		line = "."+myThis[1];

		console.log("line= "+line);
		randomCalculation(line, valNum[1]);
	});
}

function randomAll(button){
	$(button).click(function(){
		line = $(".box-lines .col2");
		for(var i=1; i <= line.length; i++){
			randomCalculation(".num"+i, i);
		}
	});
}

function clearNum(button){
//	$(".num1 numbers gwp").hasClass("active")
	$(button).click(function(){
		myThis = $(this).closest(".col2").attr('class').split(" ");
		valNum = myThis[1].split("num");
		line = "."+myThis[1];

		$(line+" .values .active").toggleClass('active');

		numberCount[valNum[1]] = 0;
		starCount[valNum[1]] = 0;
		totalCount[valNum[1]] = 0;
		checkMark(valNum[1])
	});
}

function clearNumAll(button){
	$(button).click(function(){
		line = $(".box-lines .col2");
		$(".box-lines .values .active").toggleClass('active');
		for(var i=1; i <= line.length; i++){
			numberCount[i] = 0;
			starCount[i] = 0;
			totalCount[i] = 0;
			checkMark(i)
		}
	});
}

$(function(){
	//$(".random-all").css("margin-right","-15px"); // Fix initial positioning of a button
	playLine('.numbers .btn', "number");
	playLine('.stars .ico-star-out', "star");
	randomNum(".random");
	clearNum(".clear");
	randomAll(".random-all");
	clearNumAll(".clear-all");

	$(".li-play").addClass("active");
});
