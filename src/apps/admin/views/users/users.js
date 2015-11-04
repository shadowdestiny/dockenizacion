//EMTD add more functions
var ajaxFunctions = {
    edit : function (params) {
        $.ajax({
            url: '/admin/users/edit/',
            data: params,
            type: 'POST',
            dataType: "json",
            success: function(json) {
                if(typeof json.message !== 'undefined'){

                }else if(typeof json.message !== 'undefined') {

                }else if(typeof json.result !== 'undefined'){
                    $('.crud-user .sub-title.purple').text('Edit user');
                    $('.box-user-data').hide();
                    $('.crud-user').show('fast');
                    $('.form-user #name').val(json.result.OK.name);
                    $('.form-user #surname').val(json.result.OK.surname);
                    $('.form-user #email').val(json.result.OK.email);
                    $('.form-user #country').val(json.result.OK.country);
                    $('.form-user #password').val(json.result.OK.password);
                    $('.form-user #address').val(json.result.OK.address);
                    $('.form-user #zip').val(json.result.OK.zip);
                    $('.form-user #balance').val(json.result.OK.balance);
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

    $('.form-user .btn-primary').on('click',function(){
        var params = $('.form-user').serialize();
        ajaxFunctions.edit(params);
    })
    $('.form-user .btn-danger').on('click',function(){
        $('.crud-user').hide('fast');
        $('.box-user-data').show();
    })
});