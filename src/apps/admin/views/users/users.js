//EMTD add more functions
var ajaxFunctions = {
    edit : function (params) {
        $.ajax({
            url: '/admin/users/edit/',
            data: params,
            type: 'POST',
            dataType: "json",
            success: function(json) {
                if(json.result == 'OK'){
                    $('.alert-success').show();
                }else if(json.result == 'KO') {
                    $('.alert-danger').show();
                }else if(typeof json.result_view !== 'undefined' && json.result_view == 'OK'){
                    $('.crud-user .sub-title.purple').text('Edit user');
                    $('.box-user-data').hide();
                    $('.crud-user').show('fast');
                    $('.form-user #name').val(json.value.name);
                    $('.form-user #surname').val(json.value.surname);
                    $('.form-user #email').val(json.value.email);
                    $('.form-user #country').val(json.value.country);
                    $('.form-user #password').val(json.value.password);
                    $('.form-user #address').val(json.value.address);
                    $('.form-user #zip').val(json.value.zip);
                    $('.form-user #balance').val(json.value.balance);
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