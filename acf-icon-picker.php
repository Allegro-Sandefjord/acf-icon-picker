<?php

/*
Plugin Name: ACF Icon Picker
Plugin URI: 
Description: Search and select icons on from Google Material Icosn or Font Awesome without leaving the page.
Version: 1.0
Author: Robert Sather
Author URI: https://github.com/roberts91
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if( ! defined( 'ABSPATH' ) ) exit;

if( ! class_exists( 'icon_picker' ) ) {
	require_once plugin_dir_path( __FILE__ ) . 'inc/icon-picker.php';
}

load_plugin_textdomain( 'acf-icon-picker', false, dirname( plugin_basename(__FILE__) ) . '/lang/' ); 

function include_field_types_icon_picker( $version ) {	
	include_once('acf-icon-picker-v5.php');	
}

add_action('acf/include_field_types', 'include_field_types_icon_picker');	

function register_fields_icon_picker() {
	include_once('acf-icon-picker-v4.php');
}

add_action('acf/register_fields', 'register_fields_icon_picker');