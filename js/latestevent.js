Fabs_Facebook.latestevent = function(uid, ctId, params, cacheKey){
    var $=jQuery;
    
    function Event(data){
        $.extend(this,data);
		this.date = Date.parseISO8601(this.start_time);
		//this.date.setTime(this.date.getTime()-(3*60*60*1000));
    }
    $.extend( Event.prototype, {
        bigDate : function(){
            return this.date.format('dddd mmmm dd yyyy');
        },
		formattedDate : function(format){
			return this.date.format(format);
		},
		intro : function(length){
			legnth = length || 200;
			if( this.description.length <= length ){
				return this.description;
			}
			var i = this.description.substr(0,length).lastIndexOf(' ');
			return this.description.substr(0,i)+'...';
		}
    });
    function write(data){
        $('#'+ctId).empty();
        if( data && data.data.length > 0 ){
			var event = new Event(data.data[data.data.length-1]);
			if( true || event.date > new Date() ){
				$('#fabs-facebook-latestevent-tmpl')
					.tmpl(event)
					.appendTo('#'+ctId);
				return;
			}
        }
        $('#'+ctId).html('Check back soon...');
    }
    $( function(){
		$('#'+ctId).html('loading...');
		var _params = {
            //since: 'yesterday',
            fields:'location,venue,description,name'
        };
        if( $.isPlainObject(params) ){
			$.extend(_params,params);
		}
		var cache = Fabs_Facebook.cache(cacheKey);
		if( cache ) write( cache );
        $.fabs_facebook(uid+'/events', _params, write);
    });
};
