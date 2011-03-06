jQuery.fabs_facebook = function(path, params, callback)
{
    if( jQuery.isFunction(params)){
        callback = params;
    }
    params = params || {};
    params = jQuery.extend(params, {
        action:'fabs_facebook_ajax',
        path:path
    });
    jQuery.getJSON(Fabs_Facebook.ajaxurl,params,callback);
}