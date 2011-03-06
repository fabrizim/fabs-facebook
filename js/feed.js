Fabs_Facebook.feed = function(uid, ctId, params, cacheKey){
    var $=jQuery, limit;
    
    function Entry(data){
        $.extend(this,data);
        // strip any html in the description
        if( this.description ){
            this.description = this.description.replace(/<.*?>/ig,'');
        }
		if( this.created_time ){
			this.created_time = Date.parseISO8601(this.created_time);
		}
    }
    $.extend( Entry.prototype, {
        timeago : function(){
            return jQuery.timeago(this.created_time);
        },
		getHeadline : function(){
			if( this.type != 'status' ){
				return this.message;
			}
			var i = this.message.indexOf('\n');
			return this.message;
		},
		formattedDate : function(format){
			return this.created_time.format(format);
		},
        niceLikes : function(){
            if( this.likes ){
                return '<i class="sprite-icon icon-like"></i> '+this.likes.count;
            }
            return '';
        },
        niceComments : function(){
            if( this.comments && this.comments.count ){
                return '<i class="sprite-icon icon-comment"></i> '+this.comments.count+' '+(this.comments.count>1?'Comments':'Comment');
            }
            return '';
        },
        shortDescription : function(length){
            length = length || 200;
            if( this.description ){
                if( this.description.length < length ){
                    return this.description;
                }
                var str = this.description.substr(0,length+1);
                var i = str.lastIndexOf(' ');
                return str.substring(0,i)+'...';
            }
            return '';
        },
        isPost : function(){
            return this.id && this.type;
        },
        getPostLink : function(){
            return 'http://facebook.com/'
                +this.id.substr(0,this.id.indexOf('_'))
                +'/posts/'
                +this.id.substr(this.id.indexOf('_')+1);
        }
    });
    
    function Feed(data, uid){
        $.extend(this,data);
        this.uid = uid;
        var self = this;
        this.createEntries(this.data);
        
    }
    $.extend( Feed.prototype, {
        
        createEntries : function(ar){
            var self = this;
            $.each( ar, function(i,item){
                ar[i] = new Entry(item);
                if( item.comments ){ self.createEntries(item.comments.data); }
            });
        },
        
        limit: function(number){
            if( number ){
                this.data = this.data.splice(0,number);
            }
        },
        
        filter: function(fn){
            var ret = [];
            $.each( this.data, function(index,item){
                if( fn(item) ) ret.push(item);
            });
            return ret;
        },
        
        filterOutFriends : function(){
            var uid=this.uid;
            this.data = this.filter( function(item){
                return ( item && item.from && item.from.id == uid );
            });
        }
    });
    
    function loadComments(e){
        // $(e.target).html('Loading...');
        var tmplItem = $(e.target).tmplItem();
        var data = tmplItem.data;
        if( data.comments.count == data.comments.data.length ) return;
        $.fabs_facebook(data.id+'/comments', function(comments){
            if( !comments ){
                return;
            }
            data.comments.data = [];
            $.each(comments.data, function(index, comment){
                data.comments.data.push(new Entry(comment));
            });
            tmplItem.update();
            data._commentsLoaded = true;
        });
    }
   
    
    function write(data){
		$('#'+ctId).empty();
        var feed = new Feed(data, uid);
        feed.filterOutFriends();
        feed.limit(limit);
        $('#fabs-facebook-feed-tmpl')
            .tmpl(feed)
            .appendTo('#'+ctId);
            
        // get all the show comments boxes and add a click event
        $('#'+ctId+' .comments-show-all').click(loadComments);
        $('#'+ctId+' .comments-link').live('click', function(e){
            var tmplItem = $(e.target).tmplItem();
            var data = tmplItem.data;
            var id = e.target.id.replace(/^fabsfb\-comments\-link\-/, '');
            var box = $('#fabsfb-comments-box-'+id);
            var fn = box.css('display') == 'none' ? 'show' : 'hide';
            if( fn=='show' ){
                data.displayComments=true;
                loadComments(e);
            }
            $('#fabsfb-comments-box-'+id)[fn]();
        });
    }
    $( function(){
		if( params && params.limit ){
            limit = params.limit;
            delete params.limit;
        }
        if( !$.isPlainObject(params) ){
			params = {};
		}
		var cache = Fabs_Facebook.cache(cacheKey);
		if( cache ) write( cache );
        $.fabs_facebook(uid+'/feed', params, write);
    });
};
