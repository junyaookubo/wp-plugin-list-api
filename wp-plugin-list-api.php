<?php
/*
Plugin Name: WP Plugin List API
Description: インストール済みのプラグインに関するAPIを作成するプラグインです。
Version: 1.0
Author: World Utility Co., Ltd.
*/

if ( ! defined( 'ABSPATH' ) ) exit;

add_action('rest_api_init', function() {
	register_rest_route( 'wp/custom', '/get_plugins', [
		'methods' => 'GET',
		'callback' => 'get_plugins_api',
		'permission_callback' => function() { return true; }
	]);
});
function get_plugins_api() {
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    $plugins = get_plugins();
    $all = array();
    if(!empty($plugins)){
        foreach($plugins as $path => $plugin){
            if(is_plugin_active($path)){
                $all[] = array(
                    'site' => get_option('home'),
                    'status' => true,
                    'name' => $plugin['Name']
                );
            }else{
                $all[] = array(
                    'site' => get_option('home'),
                    'status' => false,
                    'name' => $plugin['Name']
                );
            }
        }
    }
    return new WP_REST_Response( $all, 200 );
}