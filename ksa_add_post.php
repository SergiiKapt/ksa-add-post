<?php
/*
Plugin Name: KSASK add post shortcode
Plugin URI: https://ksask.net/
Description: Adding post from form shortcode.
Author: Sergii
Author URI: https://ksask.net/
Text Domain: ksask-add-post
Domain Path: /languages/
Version: 1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
    die( 'Invalid request.' );
}

define('KSA_ADDP', 1);

define('KSA_ADDP_P', __FILE__);

define('KSA_ADDP_P_DIR', untrailingslashit(dirname(KSA_ADDP_P)));

define( 'KSA_ADDP_P_URI', plugin_dir_url( __FILE__ ) );

if ( ! class_exists( 'KSA_Add_Post_Shortcode' ) ) :
    require_once KSA_ADDP_P_DIR . '/KSA_Add_Post_Shortcode.php';

    KSA_Add_Post_Shortcode::run();
endif;