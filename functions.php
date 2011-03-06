<?php
function & fabs_facebook()
{
    static $facebook;
    if( !isset($facebook) ){
        $facebook = new Facebook(array(
            'appId'  => get_option('fabs_facebook_application_id'),
            'secret' => get_option('fabs_facebook_application_secret'),
            'cookie' => true,
        ));
    }
    return $facebook;
}

function fabs_facebook_login_url()
{
    return fabs_facebook()->getLoginUrl(array(
        'req_perms' => 'offline_access,manage_pages'
    ));
}


function fabs_facebook_access_token($id)
{
	$accounts =& fabs_facebook_accounts();
	if( isset($accounts[$id]) ){
		return $accounts[$id]['access_token'];
	}
	return false;
}

function fabs_facebook_cache($path, $params=array())
{
	$cacheFile = fabs_facebook_cache_path($path, $params);
	if( file_exists($cacheFile) ){
		
		$return = @unserialize(file_get_contents($cacheFile));
		if( $return && !empty($return) ){
			return $return;
		}
	}
	return fabs_facebook_api($path, $params);
}

function fabs_facebook_cache_path($path, $params=array())
{
	$key = fabs_facebook_cache_key($path, $params);
	$cachePath = FABS_FACEBOOK_PATH.'/cache';
	$cacheFile = $cachePath.'/'.$key;
	@mkdir($cachePath);
	//touch($cacheFile);
	return $cacheFile;
}

function fabs_facebook_cache_key($path, $params=array())
{
	return md5($path.serialize($params));
}

function fabs_facebook_api($path,$params=array())
{
	$path = preg_replace('#^\/#', '',$path);
	$parts = explode('/',$path);
	if( $parts && count($parts) ){
		if( $token = fabs_facebook_access_token($parts[0]) ){
			$params['access_code'] = $token;
		}
	}
	$return = fabs_facebook()->api('/'.$path,'GET',$params);
	$cacheFile = fabs_facebook_cache_path($path, $params);
	file_put_contents($cacheFile, serialize($return));
	return $return;
}

function fabs_facebook_api_ajax()
{
	check_admin_referer( 'fabs-facebook' );
	$path = @$_REQUEST['path'];
	unset($_REQUEST['path']);
	unset($_REQUEST['action']);
	$return = fabs_facebook_api($path, $_REQUEST);
	header('Content-Type:text/json');
	die(json_encode($return));
}

function & fabs_facebook_accounts()
{
	static $accounts;
	if( !isset($accounts) ){
		$data = get_option('_fabs_facebook_accounts');
		$accounts = is_array($data) ? $data : array();
	}
	return $accounts;
}

function fabs_facebook_add_account($id, $access_token, $name, $type='Person')
{
	$accounts =& fabs_facebook_accounts();
	$accounts[$id] = array(
		'access_token' => $access_token,
		'name' => $name,
		'id' => $id,
		'type' => $type
	);
	update_option('_fabs_facebook_accounts', $accounts ); 
}

function fabs_facebook_delete_account($id)
{
	$accounts =& fabs_facebook_accounts();
	unset($accounts[$id]);
	update_option('_fabs_facebook_accounts', $accounts ); 
}

function fabs_facebook_default_account()
{
	return get_option('fabs_facebook_default_account');
}

function fabs_facebook_jquery_tmpl($name, $id=false)
{
	static $written=array();
	if( is_array($name) ){
		foreach( $name as $n )fabs_facebook_jquery_tmpl($n, $id ? $n.'-'.$id: false);
		return;
	}
	if( isset($written[$name]) ) return;
    $content = '';
    
    // search for the template
	$path = str_replace('-','/',$name);
    $override = get_theme_root().'/'.get_stylesheet().'/fabs-facebook/tmpl/'.$path.'.html';
    $default = dirname(__FILE__).'/tmpl/'.$path.'.html';
    
    if( file_exists( $override ) ){
        $content = file_get_contents( $override );
    }
    else if( file_exists( $default ) ){
        $content = file_get_contents( $default );
    }
    
    if( !$id ) $id ="fabs-facebook-$name-tmpl";
    ?>
    <script id="<?php echo $id; ?>" type="text/x-jquery-tmpl">
        <?php echo $content; ?>
    </script>
    <?php
	$written[$name] = true;
}

function fabs_facebook_js($key)
{
	// need to include the facebook library once.
	static $_loaded = array();
	if( count($_loaded ) && isset($_loaded[$key]) ){
		return;
	}
	if( !count($_loaded) ){
		$dateFormat = get_bloginfo('wpurl')."/wp-content/plugins/fabs-facebook/js/date.format.js";
		$jqueryHistory = get_bloginfo('wpurl')."/wp-content/plugins/fabs-facebook/js/jquery.history.js";
		?>
	<script type="text/javascript" src="<?= $dateFormat ?>"></script>
	<script type="text/javascript" src="<?= $jqueryHistory ?>"></script>
	<script type="text/javascript" src="http://ajax.microsoft.com/ajax/jquery.templates/beta1/jquery.tmpl.min.js"></script>
	<script type="text/javascript">
	Fabs_Facebook = Fabs_Facebook || {};
	(function(){
		var cache = {};
		Fabs_Facebook.cache = function(key,value){
			if( value === undefined ){
				return cache[key];
			}
			cache[key] = value;
		};
	
	})();
	</script>
		<?php
	}
	// now include the js file
	$url = plugins_url("/js/$key.js", __FILE__);
	?>
	<script type="text/javascript" src="<?= $url ?>"></script>
	<?php
	$_loaded[$key] = true;
}