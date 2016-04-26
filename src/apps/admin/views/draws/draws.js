var ajaxFunctions = {
    edit : function (params,callback) {
        $.ajax({
            url: '/admin/draws/edit/',
            data: params,
            type: 'POST',
            dataType: "json",
            success: function(model) {
                if(model.result == 'OK') {
                    $('.alert-success').show();
                    $('.crud-draw').hide('fast');
                    $tr = '';
                    $.each(model.value,function(i,v){
                        $tr += '<tr>';
                        $tr += '<td class="date">'+v.draw_date+'</td>';
                        $tr += '<td class="jackpot">&euro; '+v.jackpot+'</td>';
                        $regular_numbers = v.regular_numbers;
                        $tr += '<td class="numbers">';
                        $.each($regular_numbers, function(i,n){
                            if(n != null ){
                                $tr += '<span class="num">'+n+'</span>';
                            }else{
                                $tr += '<span class="num"></span>';
                            }
                        });
                        $lucky_numbers = v.lucky_numbers;
                        $.each($lucky_numbers,function(i,l){
                            if(l != null){
                                $tr += '<span class="num yellow">'+l+'</span>';
                            }else{
                                $tr += '<span class="num yellow"></span>';
                            }
                        });
                        $tr += '</td>';
                        $tr += '<td class="action">';
                        $tr += '<a href="javascript:void(0)" data-id='+v.id+' class="btn btn-primary">Edit</a>';
                        $tr += '</td>';
                        $tr += '</tr>';
                    });
                    $('.table tbody').html('');
                    $('.table tbody').append($tr);
                    $('.box-draw-data').show();
                }else{
                    $('.alert-danger').show();
                }
            },
            error: function (xhr, status, errorThrown) {
            },
        });
    },
    view: function(params) {
        $.ajax({
            url: '/admin/draws/view/',
            data: params,
            type: 'POST',
            dataType: 'json',
            success: function(model) {
                if(model.result == 'OK') {
                    $('.crud-draw .sub-title.purple').text('Edit draw');
                    $('.box-draw-data').hide();
                    $('.crud-draw').show('fast');
                    $('#update-date').val(model.value.draw_date);
                    $('#update-number').val(model.value.regular_numbers);
                    $('#update-star-number').val(model.value.lucky_numbers);
                    $('#update-value').val(model.value.jackpot);
                    $('#id_draw').val(model.value.id);
                    $('#page').val(model.page);
                    $tr = '';
                    $.each(model.value.break_down,function(i,v){
                        $tr += '<tr>';
                        $tr += '<td class="match"><strong>'+ v.name+ '</strong></td>';
                        $tr += '<td class="prize"><span class="value">&euro;</span><input type="hidden" name="breakdown['+i+'][0]" value="'+ v.name+'"/><input type="text" name="breakdown['+i+'][1]" class="input" value="'+ v.lottery_prize+'"></td>';
                        $tr += '<td class="winners"><input type="text" class="input" name="breakdown['+i+'][2]" value="'+v.winners+'"></td>';
                        $tr += '</tr>';
                    });
                    $tr += '<input type="hidden" name="id_draw" value="'+model.value.id+'" />';
                    $('.table-breakdown tbody').html('');
                    $('.table-breakdown tbody').append($tr);
                } else {
                    $('.alert-danger').show();
                }
            },
            error: function (xhr, status, errorThrown) {
                //EMTD manage errrors
            },
        });
    },
    search: function (params) {
        $.ajax({
            url: '/admin/draws/search/',
            data: params,
            type: 'POST',
            dataType: 'json',
            success: function(model) {
                if(model.result == 'OK'){
                    $tr = '<tr>';
                    $tr += '<td class="date">'+model.value.draw_date+'</td>';
                    $tr += '<td class="jackpot">&euro; '+model.value.jackpot+'</td>';
                    $regular_numbers = model.value.regular_numbers;
                    $tr += '<td class="numbers">';
                    $.each($regular_numbers, function(i,v){
                        $tr += '<span class="num">'+v+'</span>';
                    })
                    $lucky_numbers = model.value.lucky_numbers;
                    $.each($lucky_numbers,function(i,v){
                        $tr += '<span class="num yellow">'+v+'</span>';
                    });
                    $tr += '</td>';
                    $tr += '<td class="action">';
                    $tr += '<a href="javascript:void(0)" data-id='+model.value.id+' class="btn btn-primary">Edit</a>';
                    $tr += '</td>';
                    $('.table tbody').html('');
                    $('.table tbody').append($tr);
                }else{
                    $('.alert-danger strong').text('No data found with this date');
                    $('.alert-danger').show();
                }
            },
            error: function( xhr,status,errorThrown) {
                $('.alert-danger strong').text('An error ocurred');
                $('.alert-danger').show();
            },
        });
    },

    editBreakDown: function(params){
        $.ajax({
            url: '/admin/draws/editBreakDown/',
            data: params,
            type: 'POST',
            dataType: 'json',
            success: function(model) {
                if(model.result == 'OK'){
                    $tr = '<tr>';
                    $tr += '<td class="date">'+model.value.draw_date+'</td>';
                    $tr += '<td class="jackpot">&euro; '+model.value.jackpot+'</td>';
                    $regular_numbers = model.value.regular_numbers;
                    $tr += '<td class="numbers">';
                    $.each($regular_numbers, function(i,v){
                        $tr += '<span class="num">'+v+'</span>';
                    })
                    $lucky_numbers = model.value.lucky_numbers;
                    $.each($lucky_numbers,function(i,v){
                        $tr += '<span class="num yellow">'+v+'</span>';
                    });
                    $tr += '</td>';
                    $tr += '<td class="action">';
                    $tr += '<a href="javascript:void(0)" data-id='+model.value.id+' class="btn btn-primary">Edit</a>';
                    $tr += '</td>';
                    $('.table tbody').html('');
                    $('.table tbody').append($tr);
                }else{
                    $('.alert-danger strong').text('No data found with this date');
                    $('.alert-danger').show();
                }
            },
            error: function(xhr,status,errorThrown) {
                //$('.alert-danger strong').text('Error inserting data. Please check fields are numeric values.');
                $('.alert-danger').show();
            },
        });
    }
};
$(function(){
    $('body').on('click','.action .btn-primary',function(){
        var id = $(this).data('id');
        var page = $('#page').val();
        params = 'id='+id+'&page='+page;
        ajaxFunctions.view(params);
    });
    $('.form-draw .btn-primary').on('click',function(){
        var params = $('.form-draw').serialize();
        ajaxFunctions.edit(params);
    });
    $('.submitbreakdown').on('click',function(){
        var params = $('.form-breakdown').serialize();
        ajaxFunctions.editBreakDown(params);
    });
    $('.form-draw .btn-danger').on('click',function(){
        $('.crud-draw').hide('fast');
        $('.box-draw-data').show();
    });
    $('#search').datepicker({
        dateFormat : 'yy-m-d',
        onSelect: function (date) {
            // Your CSS changes, just in case you still need them
            var params = 'date='+date;
            ajaxFunctions.search(params);
        }
    });
    $(document).on('keypress','input',function(e){
        var pattern = /^[0-9\\,]+$/;
        var code = e.which
        var chr = String.fromCharCode(code);
        if(!pattern.test(chr)){
            e.preventDefault();
        }
    });
});