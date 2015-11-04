//EMTD add more functions
var ajaxFunctions = {
    edit : function (params) {
        $.ajax({
            url: '/admin/draws/edit/',
            data: params,
            type: 'POST',
            dataType: "json",
            success: function(json) {
                if(typeof json.result !== 'undefined'){
                    $('.crud-user .sub-title.purple').text('Edit user');
                    $('.crud-user').show('fast');
                }
            },
            error: function (xhr, status, errorThrown) {
                //EMTD manage errrors
            },
        });
    },
};
$(function(){
    //if($(body).hasClass('jackpot')){
        $('.action .btn-primary').on('click',function(){
            alert('llega');
            var id = $(this).data('id');
            params = 'id='+id;
            ajaxFunctions.edit(params);
        });
    //}



    $('.form-user .btn-primary').on('click',function(){
        var params = $('.form-user').serialize();
        ajaxFunctions.edit(params);
    })
    $('.form-user .btn-danger').on('click',function(){
        $('.crud-user').hide('fast');
    })
});