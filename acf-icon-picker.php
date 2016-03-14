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

$acf_ip_includes = array(
    'config.php',                               // Initial config
    'lib/acf-icon-picker-helper.php',           // Helpers
    'lib/acf-icon-picker-frontend.php',         // Front-end dependencies include
    'lib/acf-icon-picker-options.php',          // Optionspage
);

foreach ($acf_ip_includes as $include_file) {
    if (!file_exists(__DIR__ . '/' . $include_file)) {
        trigger_error(sprintf(__('Error locating %s for inclusion', 'acf-icon-picker'), $include_file), E_USER_ERROR);
    }
    else
    {
        require_once __DIR__ . '/' . $include_file;
    }
}

// Advanced Custom Field 5 >
add_action('acf/include_field_types', function ( $version ) {	
	include_once('lib/acf-icon-picker-v5.php');	
});	

// Advanced Custom Field 4
add_action('acf/register_fields', function() {
	include_once('lib/acf-icon-picker-v4.php');
});