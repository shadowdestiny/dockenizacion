//EMTD add more functions
var ajaxFunctions = {
    edit : function (params,callback) {
        $.ajax({
            url: '/admin/draws/edit/',
            data: params,
            type: 'POST',
            dataType: "json",
            success: function(json) {
                if(json.result == 'OK'){
                    $('.alert-success').show();
                }else if(json.result == 'KO') {
                    $('.alert-danger').show();
                }else if(typeof json.result_view !== 'undefined' && json.result_view == 'OK'){
                    $('.crud-draw .sub-title.purple').text('Edit draw');
                    $('.box-draw-data').hide();
                    $('.crud-draw').show('fast');
                    $('#update-date').val(json.value.draw_date);
                    $('#update-number').val(json.value.regular_numbers);
                    $('#update-star-number').val(json.value.lucky_numbers);
                    $('#update-value').val(json.value.jackpot);
                    $('#id_draw').val(json.value.id);
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
    }
};
$(function(){

    $('body').on('click','.action .btn-primary',function(){
        var id = $(this).data('id');
        params = 'id='+id;
        ajaxFunctions.edit(params);
    });
    $('.form-draw .btn-primary').on('click',function(){
        var params = $('.form-draw').serialize();
        ajaxFunctions.edit(params);
    })
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
});