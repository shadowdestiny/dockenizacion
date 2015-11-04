//EMTD add more functions
var ajaxFunctions = {
    edit : function (params,callback) {
        $.ajax({
            url: '/admin/draws/edit/',
            data: params,
            type: 'POST',
            dataType: "json",
            success: function(json) {
                if(typeof json.message !== 'undefined'){

                }else if(typeof json.message !== 'undefined') {

                }else if(typeof json.result !== 'undefined'){
                    $('.crud-draw .sub-title.purple').text('Edit draw');
                    $('.box-draw-data').hide();
                    $('.crud-draw').show('fast');
                    $('#update-date').val(json.result.OK.draw_date);
                    $('#update-number').val(json.result.OK.regular_numbers);
                    $('#update-star-number').val(json.result.OK.lucky_numbers);
                    $('#update-value').val(json.result.OK.jackpot);
                    $('#id_draw').val(json.result.OK.id);
                }
            },
            error: function (xhr, status, errorThrown) {
                //EMTD manage errrors
            },
        });
    },
};
$(function(){
        $('.action .btn-primary').on('click',function(){
            var id = $(this).data('id');
            params = 'id='+id;
            ajaxFunctions.edit(params);
        });
    $('.form-draw .btn-primary').on('click',function(){
        var params = $('.form-draw').serialize();
        console.log(params);
        ajaxFunctions.edit(params);
    })
    $('.form-draw .btn-danger').on('click',function(){
        $('.crud-draw').hide('fast');
        $('.box-draw-data').show();
    })
});