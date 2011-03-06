<?
/*
Plugin Name: Fabs Facebook
Plugin URI: http://www.owlwatch.com
Description: Plugin to integrate Facebook content directly on your site.
Author: Mark Fabrizio Jr.
Author URI: http://www.owlwatch.com
Version: 1.0
*/
define('FABS_FACEBOOK_PATH', dirname(__FILE__));

require_once(FABS_FACEBOOK_PATH.'/library/facebook/facebook.php');
require_once(FABS_FACEBOOK_PATH.'/functions.php');
require_once(FABS_FACEBOOK_PATH.'/shortcodes.php');
require_once(FABS_FACEBOOK_PATH.'/template.php');
require_once(FABS_FACEBOOK_PATH.'/widgets.php');

// add the ajax calls
add_action('wp_ajax_fabs_facebook_ajax', 'fabs_facebook_api_ajax');
add_action('wp_ajax_nopriv_fabs_facebook_ajax', 'fabs_facebook_api_ajax');

// add a jQuery plugin for fabs facebook
add_action('init', 'fabs_facebook_init');
function fabs_facebook_init()
{
	wp_enqueue_script( 'fabs-facebook', plugin_dir_url( __FILE__ ) . 'js/jquery.fabs_facebook.js', array( 'jquery'));
	// and add ajax url to namespace
	wp_localize_script( 'fabs-facebook', 'Fabs_Facebook', array( 'ajaxurl' => wp_nonce_url( admin_url('admin-ajax.php'), 'fabs-facebook' ) ) );
}


// add the style
add_action('wp_print_styles', 'fabs_facebook_style');
add_action('admin_init', 'fabs_facebook_style');

function fabs_facebook_style() {
	wp_register_style('fabs-facebook', WP_PLUGIN_URL . '/fabs-facebook/style.css');
	wp_enqueue_style( 'fabs-facebook');
	
	wp_register_script( 'timeago', WP_PLUGIN_URL.'/fabs-facebook/js/jquery.timeago.js');
	wp_enqueue_script( 'timeago');
}

add_action( 'admin_menu', 'fabs_facebook_admin_menu' );
function fabs_facebook_admin_menu()
{
    $page = add_options_page('Fabs Facebook Settings', 'Fabs Facebook', 'administrator', 'fabs_facebook', 'fabs_facebook_manage_settings');
    add_action('admin_init', 'fabs_facebook_register_settings');
}

function fabs_facebook_register_settings()
{
    register_setting( 'fabs-facebook', 'fabs_facebook_application_id' );
	register_setting( 'fabs-facebook', 'fabs_facebook_application_secret' );
	register_setting( 'fabs-facebook', '_fabs_facebook_delete_accounts' );
	register_setting( 'fabs-facebook', 'fabs_facebook_default_account' );
}

function fabs_facebook_manage_settings()
{
	// check for a session callback variable
	$session = @$_REQUEST['session'];
	if( $session ){
		
		// from what i've seen, all that gets passed back is this session variable.
		$session = json_decode(stripslashes($session));
		
		// okay, lets save the authorization
		$uid = $session->uid;
		$access_token = $session->access_token;
		fabs_facebook_add_account( $uid, $access_token, 'temp');
		
		// try to get accounts
		$accounts = fabs_facebook_api($uid.'/accounts');
		if( $accounts && $accounts['data'] ){
			foreach( $accounts['data'] as $account){
				fabs_facebook_add_account($account['id'], $account['access_token'], $account['name'], $account['category']);
			}
		}
		
		$user = fabs_facebook_api($uid);
		if( $user && $user['id'] ){
			fabs_facebook_add_account($user['id'], $access_token, $user['name']);
		}
		
		$default = get_option('fabs_facebook_default_account');
		if( !$default || !in_array($default, fabs_facebook_accounts()) ){
			update_option('fabs_facebook_default_account', $uid);
		}
	}
	
	// option stuff that isn't taken care of by wordpress
	$delete = get_option('_fabs_facebook_delete_accounts');
		
	if( $delete ){
		foreach( $delete as $aid ){
			fabs_facebook_delete_account($aid);
		}
		update_option('_fabs_facebook_delete_accounts','');
	}
	
    include FABS_FACEBOOK_PATH.'/settings.php';
}