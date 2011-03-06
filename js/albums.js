if( !Array.prototype.indexOf ){
	jQuery.extend( Array.prototype, {
		indexOf : function(o, from){
			var len = this.length;
			from = from || 0;
			from += (from < 0) ? len : 0;
			for (; from < len; ++from){
				if(this[from] === o){
					return from;
				}
			}
			return -1;
		}
	});
}

Fabs_Facebook.albums = function(uid, ctId, params){
	
    var $=jQuery,$albumsCard,$albumCard,$photoCard,$ct,albums;
    
	function Photo(data, album){
		this.album = album;
		$.extend(this,data);
	}
	$.extend( Photo.prototype, {
		prev : function(){
			var i = this.album.photos.indexOf(this);
			i = i==0?this.album.photos.length-1:i-1;
			return this.album.photos[i];
		},
		next : function(){
			var i = this.album.photos.indexOf(this);
			i = i==this.album.photos.length-1?0:i+1;
			return this.album.photos[i];
		}
	});
	
    function Album(data, root){
		this.root = root;
        // create photos
		this.photos = [];
		var self = this;
		$.each( data.photos.data, function(index, photo){
			root.photoMap[photo.id] = new Photo(photo, self);
			self.photos.push(root.photoMap[photo.id]);
		});
		delete data.photos;
		$.extend(this,data);
    }
    $.extend( Album.prototype, {
        
    });
    
    function Albums(data, uid){
       
        this.uid = uid;
		this.albumMap = {};
		this.photoMap = {};
        var self = this;
		self.albums = [];
		$.each( data.data, function(index, item){
			// ferget about dem profile pics...
			if( item.type != 'profile'){
				self.albumMap[item.id] = new Album(item, self);
				self.albums.push(self.albumMap[item.id]);
				// map the photos
			}
		});
		
    }
    $.extend( Albums.prototype, {
        
    });
	var $_activeCard=null;
	function activateCard($card){
		if( $_activeCard ){
			if( $_activeCard === $card ) return;
			$_activeCard.removeClass('active-card');
		}
		$card.addClass('active-card');
		$_activeCard = $card;
	}
	
	function albumPage(album){
		$albumCard.empty();
		$albumCard.removeClass('fb-loading');
		activateCard( $albumCard );
		document.title = 'Album: '+album.name;					
		$('#fabs-facebook-album-tmpl')
			.tmpl(album)
			.appendTo($albumCard);
	}
    function photoPage(photo){
		$photoCard.empty();
		$photoCard.removeClass('fb-loading')
		document.title='Photo '+photo.id+' in Album '+photo.album.name;;
		activateCard( $photoCard );
		$('#fabs-facebook-photo-tmpl')
			.tmpl(photo)
			.appendTo($photoCard);
	}
    function albumsPage(){
		document.title = 'Photos';
		$albumsCard.removeClass('fb-loading');
		activateCard($albumsCard);
    }
    $( function(){
		
		var _params = {
            fields: 'photos,name,count,description,type'
        };
        if( $.isPlainObject(params) ){
			$.extend(_params,params);
		}
		
		$ct = $('#'+ctId);
		$ct.empty();
		$ct.html([
			'<div class="fabs-facebook-cards">',
				'<div class="fabs-facebook-card fabs-facebook-albums-card"></div>',
				'<div class="fabs-facebook-card fabs-facebook-album-card"></div>',
				'<div class="fabs-facebook-card fabs-facebook-photo-card"></div>',
			'</div>'
		].join(''));
		
		$albumsCard = $('.fabs-facebook-albums-card', $ct);
		$albumCard = $('.fabs-facebook-album-card', $ct);
		$photoCard = $('.fabs-facebook-photo-card', $ct);
		
		$albumsCard.addClass('fb-loading');
		activateCard( $albumsCard );
		
		
		function router(hash){
			hash = hash.replace('!/','');
			if( hash=='' ){
				albumsPage();
				return;
			}
			var parts = hash.split('/');
			if( parts[0] == 'album' ){
				// part 1 should be the idea
				var aid = parseInt(parts[1]);
				var album = albums.albumMap[aid];
				if( album ){
					albumPage(album);
				}
				else{
					alert('Album "'+aid+'" does not exist');
					albumsPage();
				}
				return;
			}
			else if( parts[0] == 'photo' ){
				var pid = parseInt(parts[1]);
				var photo = albums.photoMap[pid];
				if( photo ){
					photoPage(photo);
				}
				else{
					alert('Photo "'+pid+'" does not exist');
					albumsPage();
				}
				return;
			}
			albumsPage();
		}
		
		$.fabs_facebook(uid+'/albums', _params, function(data){
			
			// we need to write the albums
			albums = window.albums = new Albums(data, uid);
			
			// init the albums card
			$('#fabs-facebook-albums-tmpl')
				.tmpl(albums)
				.appendTo($albumsCard);
			
			$albumsCard.removeClass('fb-loading');
			
			$.history.init(router,{
				unescape:'/'
			});
		});
		$('.fabs-facebook-albums-link').live('click', function(e){
			$.history.load('!/');
		});
		$('.fabs-facebook-album-link').live('click', function(e){
			// this should be the album
			var album = $(e.target).tmplItem().data;
			if( album.album ){
				album = album.album;
			}
			$.history.load('!/album/'+album.id+'/'+album.name);
		});
		$('.fabs-facebook-photo-link').live('click', function(e){
			var photo = $(e.target).tmplItem().data;
			$.history.load('!/photo/'+photo.id+'/'+photo.album.name);
		});
		$('.fabs-facebook-photo-link-prev').live('click', function(e){
			var photo = $(e.target).tmplItem().data.prev();
			var id = '!/photo/'+photo.id+'/'+photo.album.name;
			//$photoCard.attr('id',id);
			var preload = new Image();
			preload.onload = function(){
				$.history.load(id);
			};
			preload.src = photo.images[0].source;
		});
		$('.fabs-facebook-photo-link-next').live('click', function(e){
			var photo = $(e.target).tmplItem().data.next();
			var id= '!/photo/'+photo.id+'/'+photo.album.name;
			//$photoCard.attr('id',id);
			var preload = new Image();
			preload.onload = function(){
				$.history.load(id);
			};
			preload.src = photo.images[0].source;
			
		});
    });
	
};
