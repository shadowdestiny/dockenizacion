var numberCount = [0,0,0,0,0,0,0,0,0,0,0,0,0,0];
var starCount = numberCount.slice();
var totalCount = numberCount.slice();
var hasValue = [0,0,0,0,0,0,0,0,0,0,0,0,0,0];
var maxNumbers = 5;
var maxStars = 2;
var maxColumnsInMobile = 6;

function checkMark(arrayCount){

	obj = $(".num"+arrayCount+" .ico-checkmark");

	if(numberCount[arrayCount] == maxNumbers
        &&
            starCount[arrayCount] == maxStars){
		obj.show();
		hasValue[arrayCount] = 1;
	}else{
		hasValue[arrayCount] = 0;
		obj.hide();
	}


	hasNumbers = checkNumbersInPlay(numberCount);
	hasStars = checkNumbersInPlay(starCount);


	if(numberCount[arrayCount] > 0 || starCount[arrayCount] > 0){
		$(".num"+arrayCount+" .line").addClass("number-on");
	}else{
		$(".num"+arrayCount+" .line").removeClass("number-on");
	}

	if(numberCount.length == 0 && starCount.length == 0){
		$(".fix-margin").hide();
	}else{
		$(".fix-margin").css("display","inline-block");
	}

	hasNumbers =  checkNumbersInPlay(numberCount);
	hasStars = checkNumbersInPlay(starCount);
	if(hasNumbers == false && hasStars == false){
		$(".fix-margin .clear-all").hide();
	}

	if(jQuery.inArray( 1, hasValue ) !== -1){ // check if there is a value selected
		$(".add-cart").addClass("active");
	}else{
		$(".add-cart").removeClass("active");
	}

//	console.log(arrayCount+" totalCount= "+totalCount[arrayCount]+" // numberCount= "+numberCount[arrayCount]+" // starCount= "+starCount[arrayCount]);
}

function checkNumbersInPlay(collection){
	var hasNumber = false;
	for(var i=0;i<collection.length;i++){
		if(collection[i] > 0){
			return hasNumber = true;
		}
	}
	return hasNumber;
}


function playLine(selector, type){
	$(document).on('click',selector,function(){
		myThis = $(this).closest(".myCol").attr('class').split(" ");
		valNum = myThis[1].split("num");
		line = "."+myThis[1];
        countS = starCount[valNum[1]];
        countN = numberCount[valNum[1]];
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
                if(countN <= maxNumbers){
                    numberCount[valNum[1]]++;
                }
			}else if(type == "star"){
                if(countS <= maxStars){
                    starCount[valNum[1]]++
                }
			}
			totalCount[valNum[1]]++
		}
        if(countN+1 <= maxNumbers && type == "number"){
            $(this).toggleClass('active');
        }
        if(countS+1 <= maxStars && type == "star"){
            $(this).toggleClass('active');
        }
		console.log(valNum[1]);
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

function randomNum(selector){
	$(document).on('click',selector,function(){
		myThis = $(this).closest(".myCol").attr('class').split(" ");
		valNum = myThis[1].split("num");
		line = "."+myThis[1];
		randomCalculation(line, valNum[1]);
	});
}

function randomAll(selector){
	$(document).on('click',selector, function(){
		line = $(".box-lines .myCol");
        lengthLine = line.length;
		for(var i=1; i <= lengthLine; i++){
			randomCalculation(".num"+i, i);
		}
	});
}

function clearNum(selector){
//	$(".num1 numbers gwp").hasClass("active")
	$(document).on('click',selector, function(){
		myThis = $(this).closest(".myCol").attr('class').split(" ");
		valNum = myThis[1].split("num");
		line = "."+myThis[1];

		$(line+" .values .active").toggleClass('active');

		numberCount[valNum[1]] = 0;
		starCount[valNum[1]] = 0;
		totalCount[valNum[1]] = 0;
		checkMark(valNum[1])
	});
}

function getTotalColumns(){
	return numColumns = $("div[class*='myCol num']").length;
}


function getBets(){
	//EMTD call getTotalColumns
	var numColumns = $("div[class*='myCol num']").length +1;
	var bets = [];
	for(var k=1; k < numColumns;k++){
		var num = [];
		var numCount = 0;
		var flagActive = false;
		$('#num_'+k).find('a.active').each(function(){
			if($(this).text() !== 'undefined'){
				num[numCount] = $(this).text();
				numCount++;
			}
			flagActive = true;
		});
		if(flagActive){
			bets[k] = num;
		}
	}
	return bets;
}

function newLine(){
	var numTickets = $('div.box-lines').length;
	var totalColumns = getTotalColumns();
	if(numTickets > 1){
		//EMTD show popup with a correct message
		$('')
		return false;
	}
	var classNum = $('div[class*="myCol num"]:last').attr('class').split(" ");
	var idNumber = classNum[1].slice(-1);

	var counter = totalColumns+1;
	for(i=0;i<totalColumns;i++){
		addColumn(counter);
		counter++;
	}
}

function clearNumAll(selector){
	$(document).on('click',selector,function(){
		line = $(".box-lines .myCol");
		$(".box-lines .values .active").toggleClass('active');
		lengthLine = line.length;
		for(var i=1; i <= lengthLine; i++){
			numberCount[i] = 0;
			starCount[i] = 0;
			totalCount[i] = 0;
			checkMark(i)
		}
	});
}

//EMTD add more functions
var ajaxFunctions = {
	playCart : function (params) {
		$.ajax({
			url: '/ajax/play-temporarily/temporarilyCart/',
			data: params,
			type: 'POST',
			dataType: "json",
			success: function(json) {
				if(json.result = 'OK') {
					//EMTD location to cart
					location.href = location.href;
				}
			},
			error: function (xhr, status, errorThrown) {
				//EMTD manage errrors
			},
		});
	}
};


function addColumn(position){
	var column = $($('div[class*="myCol num"]:last')).clone();
	column.find('.numbers a,.stars a,i').each(function(index){
		if($(this).hasClass('active')){
			$(this).removeClass('active');
		}
		if($(this).hasClass('ico-checkmark')){
			$(this).hide();
		}
		playLine('.myCol .num'+index+' .numbers .btn','numbers');
	});

	//Remove current id
	column.removeAttr("id");column.attr("id","num_"+position);
	column.insertAfter($($('div[class*="myCol num"]:last')));

	//Remove current classes
	var num = column.attr('class').split(' ');
	column.removeClass(num[1]);column.addClass("num"+position);
	column.find("div.line").removeClass('number-on');

	//h1 text
	column.find('h1').text('Line ' + position);
}

function columnAdapter(){
	$(".box-lines").children("div").filter(':eq(5), :eq(4), :eq(3), :eq(2)').remove();
}

function resizeAdapterColumn(){
	var totalColumns = getTotalColumns();
	if(varSize < 4){
		if(totalColumns < maxColumnsInMobile){
			addColumn(totalColumns+1);
		}
	}
}

function checkHeightColumn(){
	lastHeight = 0;
	nextAreExtra = false;
	$(".box-lines .myCol").each(function(){
		currentColH = $(this).height();
		if(currentColH < lastHeight && lastHeight > 0 || nextAreExtra){
			$(this).toggleClass('more-row');
			nextAreExtra=true;
		}
		lastHeight = currentColH;
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
	checkHeightColumn();
	$(window).resize(function(){
		resizeAdapterColumn();
		checkHeightColumn();
	});


	//Check varSize
	if(varSize >= 4){
		columnAdapter();
	}

	$(".li-play").addClass("active");
	//send played numbers to temporarily cart
	$('.add-cart').on('click',function(){
		var params = '';
		var bets = getBets();
		for(k in bets){
			if( bets.hasOwnProperty(k)){
				params += 'bet['+k+']='+ bets[k] + '&';
			}
		}
		var draw_days = $('.draw_days').val();
		var frequency = $('.frequency').val();
		var start_draw = $('.start_draw option').data('date');
		params += 'draw_days='+draw_days+'&frequency='+frequency+'&start_draw='+start_draw;
		ajaxFunctions.playCart(params);
	});

	$('.draw_days').on('change',function(){
		var filter = $(this).val();
		if(isNaN(filter)){
			$('.start_draw option').each(function() {
				$(this).css('display', 'block');
				$('.start_draw').val($(this).val());
			});
		}else {
			$('.start_draw option').each(function () {
				if ($(this).val() != filter) {
					$(this).hide();
				} else {
					$(this).show();
					$('.start_draw').val($(this).val());
				}
			})
		}
	});

	$('.add-more').on('click',function(){
		newLine();
	});

	$('.add-more').mouseover(function(){
		if($(this).hasClass("stop")){
			$('.box-more').tipr({'mode':'top'});
		}else{
			$('.box-more').unbind('mouseenter mouseleave');
		}
	});
});




