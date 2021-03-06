#
# This is an example VCL file for Varnish.
#
# It does not do anything by default, delegating control to the
# builtin VCL. The builtin VCL is called when there is no explicit
# return statement.
#
# See the VCL chapters in the Users Guide at https://www.varnish-cache.org/docs/
# and https://www.varnish-cache.org/trac/wiki/VCLExamples for more examples.

# Marker to tell the VCL compiler that this VCL has been adapted to the
# new 4.0 format.
vcl 4.0;

# Default backend definition. Set this to point to your content server.
backend default {
    .host = "backend";
    .port = "80";
    .first_byte_timeout = 300s;
}

sub vcl_recv {
    # Happens before we check if we have this in cache already.
    #
    # Typically you clean up the request here, removing cookies you don't need,
    # rewriting the request, etc.

    if (req.url ~ "\.(jpg|jpeg|gif|png|css|js|ico|xml|svg)$") {
        # Remove cookies
        unset req.http.cookie;
        return(hash);
    }
}

sub vcl_backend_response {
    # Happens after we have read the response headers from the backend.
    #
    # Here you clean the response headers, removing silly Set-Cookie headers
    # and other mistakes your backend does.

    # For static content
    if (bereq.url ~ "\.(jpg|jpeg|gif|png|css|js|ico|xml|svg)$") {

        # Remove cookies from Backend
        unset beresp.http.set-cookie;

        # Set TTL of 1h
        set beresp.ttl = 0s; # Disabled on DEV

        # Define the default grace period to serve cached content
        set beresp.grace = 0s; # Disabled on DEV
    }
}

sub vcl_deliver {
    # Happens when we have all the pieces we need, and are about to send the
    # response to the client.
    #
    # You can do accounting or modifying the final object here.

    # Remove unwanted Headers
    unset resp.http.Server;
    unset resp.http.Via;

    # Only for DEBUG
    if (obj.hits > 0) {
        set resp.http.X-Cache = "HIT";
    } else {
       set resp.http.X-Cache = "MISS";
    }

    # Only for DEBUG
    set resp.http.X-Cache-Hits = obj.hits;

}
