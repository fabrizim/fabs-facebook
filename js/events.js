Fabs_Facebook.events = function(uid, ctId, token, params){
    var $=jQuery;
    
    function Event(data){
        $.extend(this,data);
        this.date = Date.parseISO8601(this.start_time);
		this.date.setTime(this.date.getTime()-(3*60*60*1000));
    }
    $.extend( Event.prototype, {
        
        hasPast : function(){
            return (new Date()).getTime() > this.date.getTime();
        },
        
        getTime : function(){
			var f = this.hasPast() ? 'ddd, mmm dd' : 'ddd, mmm dd hh:MM tt';
            return this.date.format(f);
        }
        
    });
    
    function Events(data){
        $.extend(this,data);
        var self = this;
        $.each(this.data, function(index,e){
            self.data[index] = new Event(e);
        });
    }
    
    $.extend( Events.prototype, {
        hasUpcomingEvents : function(){
            var upcoming = false;
            $.each( this.data, function(index, event){
                
                upcoming = !event.hasPast();
                return upcoming ? false : true;
            });
            return upcoming;
        },
        hasPastEvents : function(){
            var past = false;
            $.each( this.data, function(index, event){
                past = event.hasPast();
                return past ? false : true;
            });
            return past;
        },
        getUpcomingEvents : function(){
            var upcoming = [];
            $.each( this.data, function(index, event){
                if( !event.hasPast() ){
                    upcoming.push(event);
                }
            });
            return upcoming.reverse();
        },
        getPastEvents : function(){
            var past = [];
            $.each( this.data, function(index, event){
                if( event.hasPast() ){
                    past.push(event);
                }
            });
            return past;
        },
		sort : function(){
			// resort the data array
			
		}
    });
    
    function write(events){
		$('#'+ctId).empty();
        var evs = new Events(events);
        $('#fabs-facebook-events-tmpl')
            .tmpl(evs)
            .appendTo('#'+ctId);
		var toggle = $('.fabs-facebook-past-events-toggle', $('#'+ctId));
		var past = $('.fabs-facebook-events-past');
		toggle.click( function(){
			if( past ){
				past.css('display', past.css('display')=='none'?'block':'none');
			}
			toggle.hide();
		});
    }
    $( function(){
		
		var _params = {
            fields:'location,venue,description,name,start_time,end_time'
        };
        if( $.isPlainObject(params) ){
			$.extend(_params,params);
		}
		
        $.fabs_facebook(uid+'/events', _params, write);
    });
};
