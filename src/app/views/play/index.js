var numberCount = [0,0,0,0,0,0,0,0,0,0,0,0,0,0];
var starCount = numberCount.slice();
var totalCount = numberCount.slice();
var hasValue = [0,0,0,0,0,0,0,0,0,0,0,0,0,0];
var maxNumbers = 5;
var maxStars = 2;

function checkMark(arrayCount){
	console.log(arrayCount);
	obj = $(".num"+arrayCount+" .ico-checkmark");
	console.log(obj);
	if(numberCount[arrayCount] == maxNumbers
        &&
            starCount[arrayCount] == maxStars){
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

function playLine(selector, type){
	$(document).on('click',selector,function(){
		myThis = $(this).closest(".col2").attr('class').split(" ");
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
		myThis = $(this).closest(".col2").attr('class').split(" ");
		valNum = myThis[1].split("num");
		line = "."+myThis[1];
		randomCalculation(line, valNum[1]);
	});
}

function randomAll(selector){
	$(document).on('click',selector, function(){
		line = $(".box-lines .col2");
        lengthLine = line.length;
		for(var i=1; i <= lengthLine; i++){
			randomCalculation(".num"+i, i);
		}
	});
}

function clearNum(selector){
//	$(".num1 numbers gwp").hasClass("active")
	$(document).on('click',selector, function(){
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

function getTotalColumns(){
	return numColumns = $("div[class*='col2 num']").length;
}


function getBets(){
	//EMTD call getTotalColumns
	var numColumns = $("div[class*='col2 num']").length +1;
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
	var numTickets = $('div.cols.box-lines').length;
	if(numTickets > 1){
		//EMTD show popup with a correct message
		$('')
		return false;
	}
	var classNum = $('div[class*="col2 num"]:last').attr('class').split(" ");
	var idNumber = classNum[1].slice(-1);
	$('div.cols.box-lines:first').clone().insertAfter('div.cols.box-lines:last');
	$('div[class="cols box-lines"]:last').find('div[class*="col2 num"]').each(function(){
		idNumber++;
		$(this).find('.numbers a,.stars a,i').each(function(){
			if($(this).hasClass('active')){
				$(this).removeClass('active');
			}
			if($(this).hasClass('ico-checkmark')){
				$(this).hide();
			}
			playLine('.col2 .num'+idNumber+' .numbers .btn','numbers');
		});
		var num = $(this).closest(".col2").attr('class').split(" ");
		//Remove current class
		$(this).removeClass(num[1]);$(this).addClass("num"+idNumber);
		$(".num"+idNumber+" .line").removeClass("number-on");
		//Remove current id
		$(this).removeAttr("id");$(this).attr("id","num_"+idNumber);
		//h1 text
		$(this).find('h1').text('Line ' + idNumber);
	})
}

function clearNumAll(selector){
	$(document).on('click',selector,function(){
		line = $(".box-lines .col2");
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


$(function(){
	//$(".random-all").css("margin-right","-15px"); // Fix initial positioning of a button
	playLine('.numbers .btn', "number");
	playLine('.stars .ico-star-out', "star");
	randomNum(".random");
	clearNum(".clear");
	randomAll(".random-all");
	clearNumAll(".clear-all");

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
});




