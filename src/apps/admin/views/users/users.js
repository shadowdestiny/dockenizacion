//EMTD add more functions
var ajaxFunctions = {
    edit : function (params) {
        $.ajax({
            url: '/admin/users/edit/',
            data: params,
            type: 'POST',
            dataType: "json",
            success: function(json) {
                (json.result == 'OK') ? $('.alert-success').show() : $('.alert-danger').show();
            },
            error: function (xhr, status, errorThrown) {
                //EMTD manage errrors
            },
        });
    },
    view: function (params) {
        $.ajax({
            url: '/admin/users/view/',
            data: params,
            type: 'POST',
            dataType: 'json',
            success: function(model) {
                if(model.result == 'OK') {
                    $('.crud-user .sub-title.purple').text('Edit user');
                    $('.box-user-data').hide();
                    $('.crud-user').show('fast');
                    $('.form-user #name').val(model.value.name);
                    $('.form-user #surname').val(model.value.surname);
                    $('.form-user #email').val(model.value.email);
                    $('.form-user #country').val(model.value.country);
                    $('.form-user #password').val(model.value.password);
                    $('.form-user #address').val(model.value.address);
                    $('.form-user #zip').val(model.value.zip);
                    $('.form-user #balance').val(model.value.balance);
                }
            },
            error: function(xhr, status, errorThrown) {

            },
        })
    }


};

$(function(){
    $('.action .btn-primary').on('click',function(){
        var id = $(this).data('id');
        params = 'id='+id;
        ajaxFunctions.view(params);
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