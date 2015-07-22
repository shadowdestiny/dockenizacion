var globalFunctions = {
    setCurrency : function (value) {
        console.log('ajax/userSettings/setCurrency/'+value);
        $.ajax({
            url: 'ajax/userSettings/setCurrency/'+value,
            type: 'GET',
            dataType: "json",
            success: function(json) {
                console.log('hola???');
                if(json.result = 'OK') {
                    location.href = location.href;
                }
            },
            error: function (xhr, status, errorThrown) {
                alert( "Sorry, there was a problem!" );
                console.log( "Error: " + errorThrown );
                console.log( "Status: " + status );
                console.dir( xhr );
            },
        });
    }
};
