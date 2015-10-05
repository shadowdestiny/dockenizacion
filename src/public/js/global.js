var globalFunctions = {
    setCurrency : function (value) {
        $.ajax({
            url: 'ajax/user-settings/setCurrency/'+value,
            type: 'GET',
            dataType: "json",
            success: function(json) {
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
