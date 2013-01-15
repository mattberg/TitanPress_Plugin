<?php
/*
Plugin Name: TitanPress
Plugin URI: http://www.titanpress.org
Description: A Titanium/Wordpress framework
Version: 0.1
Author: Matt Berg
Author URI: http://twitter.com/mattberg
*/

define( 'TITANPRESS_VERSION', '0.1' );
define( 'TITANPRESS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'TITANPRESS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

if ( is_admin() )
	require_once TITANPRESS_PLUGIN_PATH . '/classes/admin.php';

require_once TITANPRESS_PLUGIN_PATH . '/classes/titanpress.php';
require_once TITANPRESS_PLUGIN_PATH . '/classes/response.php';
require_once TITANPRESS_PLUGIN_PATH . '/classes/html2text.php';

$titanpress = new TitanPress();

register_activation_hook( __FILE__, array( $titanpress, 'activate' ) );
register_deactivation_hook( __FILE__, array( $titanpress, 'deactivate' ) );

add_filter( 'init', array( $titanpress, 'init' ) );
add_action( 'template_redirect', array( $titanpress, 'template_redirect' ) );

add_filter( 'rewrite_rules_array', array( $titanpress, 'rewrite_rules' ) );
add_filter( 'query_vars', array( $titanpress, 'query_vars' ) );

add_action( 'save_post', array( $titanpress, 'save_post' ) );

add_filter( 'the_title_plain', array( $titanpress, 'the_title_plain' ) );
add_filter( 'the_content_plain', array( $titanpress, 'the_content_plain' ) );
add_filter( 'the_excerpt_plain', array( $titanpress, 'the_excerpt_plain' ) );