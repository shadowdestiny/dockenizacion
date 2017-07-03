function btnShowHide(button, show, hide){
    $(button).click(function(){
        $(show).show();
        $(hide).hide();
    });
}

$(function(){
    //hacer if para cambiar a activo
    $('#funds-value,#card-cvv,#card-number').on('keypress',function(e){

        var pattern = /^[0-9\.]+$/;
        if(e.target.id == 'card-cvv' || e.target.id == 'card-number' ) {
            pattern = /^[0-9]+$/;
        }
        var codeFF = e.keyCode;
        var code = e.which
        var chr = String.fromCharCode(code);
        if(codeFF == 8 || codeFF == 37 || codeFF == 38 || codeFF == 39 || codeFF == 40 ) {
            return true;
        }
        if(!pattern.test(chr)){
            e.preventDefault();
        }
    });
});
