var numberCount = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
var starCount = numberCount.slice();
var totalCount = numberCount.slice();
var hasValue = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
var maxNumbers = 5;
var maxStars = 2;
var maxColumnsInMobile = 6;
var numLines = [];
var isAddMoreClicked = false;
var valNumCount = 0;
var starNumCount = 0;
var currentColumns = 0;

var lineObject = function(){
	this.numbers = [];
	this.stars = [];
}
var numbers = [];
var stars = [];

$(document).on("storeNum",{numColumn: 0, typeColumn: "", num: "", active: 1},
	function(e, numColumn,typeColumn,num,active) {
	var bet_line = localStorage.getItem('bet_line');
	var column = numColumn;
	var isActive = active;
	var objLine = null;

	if(window.localStorage == 'undefined'){
		//EMTD save in cookie
	}else if(bet_line != null){
		numLines = JSON.parse(bet_line);
	}

	if(typeof numLines[column] == 'undefined' || numLines[column] == null){
		objLine = new lineObject();
	}else{
		objLine = numLines[column];
	}
	if(typeColumn == 'number'){
		if(isActive){
			objLine.numbers.push(num);
		}else{
			//find item
			var item = objLine.numbers.indexOf(num);
			objLine.numbers.splice(item,1);
		}
	}else{
		if(isActive){
			objLine.stars.push(num);
		}else{
			var item = objLine.stars.indexOf(num);
			objLine.stars.splice(item,1);
		}
	}
	numLines[column] = objLine;

	//get in load page
	localStorage.setItem('bet_line', JSON.stringify(numLines));
});

function removeColumnInLocalStorage(column){
	var bet_line = localStorage.getItem('bet_line');
	if(bet_line != null){
		numLines = JSON.parse(bet_line);
	}
	var objLine = numLines[column];
	try{
		objLine.numbers = [];
		objLine.stars = [];
		numLines[column] = objLine;
		localStorage.setItem('bet_line', JSON.stringify(numLines));
	}catch(Exception){
		//do nothing
	}
}

function checkMark(arrayCount){
	obj = $(".num"+arrayCount+" .ico-checkmark");

	if(numberCount[arrayCount] == maxNumbers && starCount[arrayCount] == maxStars){
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

	if(numberCount.length == 0 && starCount.length == 0){
		$(".fix-margin").hide();
	}else{
		$(".fix-margin").css("display","inline-block");
	}

	hasNumbers =  checkNumbersInPlay(numberCount);
	hasStars = checkNumbersInPlay(starCount);
	if(hasNumbers == false && hasStars == false){
		$(".fix-margin").hide();
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

		// remove count if disabled
		if($(this).hasClass("active")){
			if(type == "number"){
				if(numberCount[valNum[1]] > maxNumbers){
					return;
				}
				numberCount[valNum[1]]--
			}else if(type == "star"){
				if(starCount[valNum[1]] > maxStars){
					return;
				}
				starCount[valNum[1]]--
			}
			totalCount[valNum[1]]--
		}else{
			if(type == "number"){
				numArr = numberCount[valNum[1]];
				valNumCount = ++numArr;
				if(valNumCount > maxNumbers){
					return;
				}
				numberCount[valNum[1]]++
			}else if(type == "star"){
				starArr = starCount[valNum[1]];
				starNumCount = ++starArr;
				if(starNumCount > maxStars){
					return;
				}
				starCount[valNum[1]]++
			}
			totalCount[valNum[1]]++
		}

		if(numberCount[valNum[1]] <= maxNumbers  && type == "number"){
			$(this).toggleClass('active');
		}
		if(starCount[valNum[1]] <= maxStars  && type == "star"){
			$(this).toggleClass('active');
		}

		var isActive = $(this).hasClass('active');

		$(document).trigger("storeNum", [ valNum[1],type,$(this).text(),isActive] );
		checkMark(valNum[1]);
		redrawTotalCost();
	});
}

function setIntervalRepetition(callback, delay, repetitions, callback2){
    var x = 0;
    var intervalID = window.setInterval(function (){
       callback();
       if (++x === repetitions){
		   callback2();
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


function persistRandomNum(line){
	var numColumn = line.match(/\d+/)[0];
	removeColumnInLocalStorage(numColumn);
	$(".box-lines "+line+" .values .numbers a.btn").each(function(){
		if($(this).hasClass('active')){
			var num = $(this).text();
			$(document).trigger("storeNum", [ numColumn, 'number', num, true] );
		}
	});
	$(".box-lines "+line+" .values .stars a.ico").each(function(){
		if($(this).hasClass('active')){
			var num = $(this).text();
			$(document).trigger("storeNum", [ numColumn, 'star', num, true] );
		}
	});
	redrawTotalCost();
}


function isMobile()
{
	try{
		document.createEvent('TouchEvent');
		if(varSize > 2){
			return true;
		}
	}catch(e){
		return false;
	}
}


function randomCalculation(line, value){
	var arr = new Array(50);
	var arr2 = new Array(11);

	for(var i=0; i < arr.length; i++){arr[i] = i+1;}
	for(var i=0; i < arr2.length; i++){arr2[i] = i+1;}
	var delay = (isMobile() || getTotalColumns() > 12) ? 0 : 50;
	var repetition = (isMobile() || getTotalColumns() > 12) ? 1 : 12;

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
	}, delay, repetition, function(){
		persistRandomNum(line);
	});

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
        lengthLine = (line.length) -1;
		for(var i=0; i <= lengthLine; i++){
			randomCalculation(".num"+i, i);
		}
	});
	redrawTotalCost();
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
		removeColumnInLocalStorage(valNum[1]);
		checkMark(valNum[1])
		redrawTotalCost();
	});
}

function getTotalColumns(){
	return numColumns = $("div[class*='myCol num']").length;
}


function getBets(){
	//EMTD call getTotalColumns
	var numColumns = $("div[class*='myCol num']").length+1;
	var bets = [];
	for(var k=0; k < numColumns;k++){
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

	var counter = totalColumns;
	for(var i=0;i<currentColumns;i++){
		addColumn(counter);
		counter++;
	}
}

function checkFillColumns(){
	var fill = true;
	$(".box-lines .myCol").each(function(){
		var checkMark = $(this).find('.line .ico-checkmark').is(':visible');
		if(!checkMark){
			fill = false;
		}
	});
	return fill;
}

function clearNumAll(selector){
	$(document).on('click',selector,function(){
		line = $(".box-lines .myCol");
		$(".box-lines .values .active").toggleClass('active');
		lengthLine = line.length;
		for(var i=0; i <= lengthLine; i++){
			numberCount[i] = 0;
			starCount[i] = 0;
			totalCount[i] = 0;
			removeColumnInLocalStorage(i);
			checkMark(i);
			redrawTotalCost();
		}
	});
}

//REFACTOR
function printPreviousPlay(col,numbers,stars){
	var column = $(".box-lines").children("div").filter(':eq('+col+')');
	for(var i=0;i<numbers.length;i++){
		column.find('.values .numbers').each(function(){
			$(this).find('a.btn').filter(function(){
				return $(this).text() == numbers[i];
			}).addClass('active');
			numberCount[col]++;
		})
	}
	for(var i=0;i<stars.length;i++){
		column.find('.values .stars').each(function(){
			$(this).find('a.ico').filter(function(){
				return $(this).text() == stars[i];
			}).addClass('active');
			starCount[col]++;
		})
	}
	checkMark(col);
}

function putNumbersPreviousPlay(numbers){
	var numbersJSON = JSON.parse(numbers);
	if(numbersJSON != null){
		for(var i=0;i < numbersJSON.length;i++){
			if(numbersJSON[i] != null){
				var numberColumns = getTotalColumns();
				if(numbersJSON[i].numbers.length > 0 || numbersJSON[i].stars.length > 0){
					if(i > (currentColumns)-1 && numberColumns < numbersJSON.length){
						newLine();
					}
					var numbers = numbersJSON[i].numbers;
					var stars = numbersJSON[i].stars;
					if(numbers || stars){
						printPreviousPlay(i,numbers,stars);
					}
				}else{
					//remove column from localstorage, is empty
					removeColumnInLocalStorage(numbersJSON[i]);
				}
			}
		}
	}

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
	newPosition = ++position;
	column.find('h1').text('Line ' + newPosition);
}

function columnAdapter(){
	$(".box-lines").children("div").filter(':eq(2), :eq(3), :eq(4), :eq(5)').each(function(){
		$(this).remove();
	})
}

function resizeAdapterColumn(){
	var totalColumns = getTotalColumns();
	if(varSize < 4){
		if(totalColumns < maxColumnsInMobile){
			addColumn(totalColumns);
		}
	}
}

function getBetsActive(){
	var totalBets = getBets();
	var numBetsFinished = 0;
	for(var i=0;i<totalBets.length;i++){
		if(typeof totalBets[i] !=  'undefined'){
			if(totalBets[i].length == 7 ){
				numBetsFinished++;
			}
		}
	}
	return numBetsFinished;
}

function redrawTotalCost(){
	var numWeeks = $('.frequency').val();
	var playDays = $('.draw_days').val().split(',').length;
	var numDraws = numWeeks * playDays;
	var price = '{{ single_bet_price }}';
	var betsActive = getBetsActive();
	var total = Number(betsActive * price * numDraws).toFixed(2);
	//EMTD put user currency
	var user_currency = "\u20AC";
	$('.box-bottom .add-cart .value').text(total + " " + user_currency);
}

function multiTask(target, activate, check){
	$(activate).prop('disabled', false);
	$(check).prop('checked', false);
	$(target).hide();
	$(".col2 .styled-select").removeClass("disabled")
}

function showAdvanced(btnShow, target, btnHide, disable, activate, check, input, select){
	$(btnShow).on('click',function(){ //Activate Play Button
		if($(target).is(':hidden')){ //Show
			$(check).prop("checked", false);
			$(target).show();
			$(select).show();
		  	$(input).hide();
			if($(select).val() != "default"){
				$(select).val('default');
			}
			$(disable).prop('disabled', 'disabled');
			resetSelect(); // _elements/jackpot-threshold.volt
		}else{
			multiTask(target, activate, check); //Hide
		}
	});
	$(btnHide).on('click',function(){ // Close button
		multiTask(target, activate, check); //Hide
	});	
}

function disableSelect(target, disable, activate, input, inputParent, select){ //Jackpot Threshold Activate/Deactivate area
   	toggleSelect(); // _elements/jackpot-threshold.volt
    $(target).on('click',function(){
        if($(target).is(":checked")){
			$(".col2 .styled-select").addClass("disabled")
        }else{
			$(".col2 .styled-select").removeClass("disabled")
        }
    });
    $(target).on('click',function(){
        if($(target).is(":checked")){
            $(disable).prop('disabled', 'disabled');
        }else{
            $(disable).prop('disabled', false);
        }
    });
}

$(function(){
	playLine('.numbers .btn', "number");
	playLine('.stars .ico-star-out', "star");
	randomNum(".random");
	clearNum(".clear");
	randomAll(".random-all");
	clearNumAll(".clear-all");
	$('.tipr-normal').tipr({'mode':'top', "styled":"normal"});
	$('.tipr-small').tipr({'mode':'top', "styled":"small"});	
	showAdvanced(".advanced", ".advanced-play", ".advanced-play .close", ".details select", ".advanced-play .col2 select", "#threshold", ".input-value",".threshold")
	disableSelect("#threshold",".advanced-play .col2 select", ".details select",".input-value input",".input-value",".threshold");

	$(window).resize(function(){
		resizeAdapterColumn();
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
			$('.start_draw option').each(function(){
				$(this).css('display', 'block');
				$('.start_draw').val($(this).val());
			});
		}else {
			$('.start_draw option').each(function(){
				if ($(this).val() != filter) {
					$(this).hide();
				} else {
					$(this).show();
					$('.start_draw').val($(this).val());
				}
			})
		}
		redrawTotalCost();
	});

	$('.frequency').on('change',function(){
		redrawTotalCost();
	})

	$('.add-more').on('click touchend', function(){
		if(isAddMoreClicked == false ){
			newLine();
			isAddMoreClicked=true;
			$('.add-more').addClass('stop');
		}else{
			if(checkFillColumns()){
				newLine();
				isAddMoreClicked=true;
				$('.add-more').addClass('stop');
			}
		}
	});


	$('.add-more').on('mouseover touchstart', function(){
		if(checkFillColumns() && isAddMoreClicked == true){
			$('.add-more').removeClass('stop');
		}
		if($(this).hasClass("stop")){
			if(!$('.tipr').length) {
				$('.box-more').tipr({'mode':'top'});
			}
		}else{
			$('.box-more').unbind('mouseenter mouseleave vclick');
		}
	});

	currentColumns = getTotalColumns();
	//check key in localstorage to get numbers in previous play
	var numbers = localStorage.getItem('bet_line');
	putNumbersPreviousPlay(numbers);
	redrawTotalCost();
});




