<?php

/*
Plugin Name: ACF Icon Picker
Plugin URI: 
Description: Search and select icons from Font Awesome, Dashicons and more without leaving WP-admin.
Version: 1.3
Text Domain: acf-icon-picker
Domain Path: /languages
Author: Robert Sæther
Author URI: https://github.com/roberts91
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

*/

if( ! defined( 'ABSPATH' ) ) exit;

// Load translation-folder
load_plugin_textdomain( 'acf-icon-picker', false, dirname( plugin_basename(__FILE__) ) . '/languages/' ); 

// Files to include
$acf_ip_includes = array(
    'lib/config.php',           // Initial config
    'lib/helpers.php',          // Helpers
    'lib/common.php',           // Common functions used both by ACF 4 and 5
    'lib/frontend-assets.php',  // Front-end dependencies includes
    'lib/options.php',          // Optionspage-class
    'lib/init.php',             // Init
);

// Loop through files
foreach ($acf_ip_includes as $include_file) {
    if (!file_exists(__DIR__ . '/' . $include_file))
    {
        trigger_error( sprintf( 'Error locating %s for inclusion', $include_file), E_USER_ERROR );
    }
    else
    {
        require_once __DIR__ . '/' . $include_file;
    }
}