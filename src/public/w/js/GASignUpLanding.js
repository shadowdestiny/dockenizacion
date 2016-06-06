 var l = document.createElement("a");
        l.href = document.referrer
        if (l.pathname == '/sign-up'){
                ga('set', 'page', '/sign-up/landing');
                ga('send', 'pageview');
        }
