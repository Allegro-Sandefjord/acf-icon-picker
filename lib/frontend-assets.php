<?php

if( ! defined( 'ABSPATH' ) ) exit;

/**
 * Add frontend-styles
 */
add_action( 'wp_enqueue_scripts', function () {
    
    // Get ACF version
    $acf_version = ACF_IP_helpers\get_acf_version();
    
    // Check if ACF 5
    if( $acf_version == '5' )
    {
        // Get inclusion-settings
        $do_custom_inclusion = ( get_field( 'acf_ip_prevent_inclusion_css', 'option' ) === true );
        $custom_inclusion = get_field( 'acf_ip_custom_inclusion', 'option' );
    }
    // ACF 4
    else
    {
        // Get inclusion-settings
        $do_custom_inclusion = ( get_option( 'acf_ip_prevent_inclusion_css' ) === '1' );
        $custom_inclusion = get_option( 'acf_ip_custom_inclusion' );
        
        // Check if is array
        if(is_array($custom_inclusion))
        {
            // Get only array keys
            $custom_inclusion = array_keys($custom_inclusion);
        }
        
    }
    
    // Get all providers    
    $providers = \ACF_IP_helpers\get_providers(true);
    
    // Get plugin directory URI
    $dir = \ACF_IP_helpers\get_plugin_url();
    
    // Loop through providers
    foreach($providers as $provider_key => $provider)
    {
        // Check if this provider should be loaded front-end
        if(
            (
                ! $do_custom_inclusion 
            ) OR
            (
                $do_custom_inclusion AND 
                (
                    is_null($custom_inclusion) OR
                    $custom_inclusion === false OR
                    (is_array($custom_inclusion) AND in_array($provider_key, $custom_inclusion))
                )
            )
        )
        {
            // Include provider
            ACF_IP_helpers\include_provider($provider);
        }
        
    }
    
} );