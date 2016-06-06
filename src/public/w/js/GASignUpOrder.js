var l = document.createElement("a");
        l.href = document.referrer
        if (l.pathname == '/cart/profile'){
        ga('set', 'page', '/sign-up/order-landing');
                ga('send', 'pageview');
        }
