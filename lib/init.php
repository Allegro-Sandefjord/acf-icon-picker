<?php

if( ! defined( 'ABSPATH' ) ) exit;

// Initiate options-class
$options = new ACF_IP_options;

// Init for Advanced Custom Field 5 >
add_action('acf/include_field_types', function ( $version ) use ($options) {	
    
    // Add v5 optionspage
    $options->init_v5();
    
    // Set ACF version
    $GLOBALS['acf_ip']['acf_version'] = '5';
    
    // Init v5 ACF-hooks
	include_once('acf-v5-init.php');	
    
});	

// Init for Advanced Custom Field 4
add_action('acf/register_fields', function() use ($options) {
    
    // Add v4 optionspage
    $options->init_v4();
    
    // Set ACF version
    $GLOBALS['acf_ip']['acf_version'] = '4';
    
    // Init v4 ACF-hooks
	include_once('acf-v4-init.php');
    
});

// Add ACF Icon Picker style- and script dependencies for WP admin
add_action('admin_enqueue_scripts', function(){
    
    // Get plugin directory URI
    $dir = \ACF_IP_helpers\get_plugin_url();
    
    // Get plugin version
    $plugin_version = \ACF_IP_helpers\get_plugin_version();
    
    // Check if should use minified files
    $minified = (\ACF_IP_helpers\get_plugin_environment() == 'production');
    
    // Add JS
    wp_register_script( 'acf-icon-picker', "{$dir}js/icon-picker" . ($minified ? '.min' : '') . ".js", array('jquery', 'algolia-autocomplete-jquery', 'algolia-search'), $plugin_version, true );
    
    // Get all providers
    $providers = ACF_IP_helpers\get_providers(true);
    
    // Pass variables to JS
    $translation_array = array(
        
        // Translations
        'trans' => array(
            'no_icon_selected' => __( 'No icon selected', 'acf-icon-picker' ),
            'unknown_provider' => __( 'Unknown provider', 'acf-icon-picker' ),
        ),
        
        // Pixeden logo
        'pixeden_logo'     => $dir . $providers['7-stroke']->icon->content,
        
    );
    wp_localize_script( 'acf-icon-picker', 'ACFIP_var', $translation_array );

    // Enqueue script
    wp_enqueue_script('acf-icon-picker');
    
    // Register CSS
	wp_register_style( 'acf-icon-picker', "{$dir}css/icon-picker" . ($minified ? '.min' : '') . ".css", array(), $plugin_version, 'all' );

	// Enqueue CSS
	wp_enqueue_style('acf-icon-picker');
    
});