<?php

namespace ACF_IP_helpers;

if( ! defined( 'ABSPATH' ) ) exit;

/**
 * Format icon from meta values to markup
 */
function format_icon( $value ) {
    
    // Check type
    if( ! isset($value['type']) OR empty($value['type']) ) return null;
    
    // Check code
    if( ! isset($value['code']) OR empty($value['code']) ) return null;
    
    // Check which type of icon
    switch($value['type'])
    {
        
        // Google Material icons
        case 'gmi':
        
            // Return markup
            return '<i class="material-icons">&#x' . $value['code'] . ';</i>';
            
        break;
        
        // Font Awesome
        case 'fa':
        
            // Return markup
            return '<i class="fa fa-' . $value['code'] . '"></i>';
            
        break;
        
        // Font Awesome
        case 'wp':
        
            // Return markup
            return '<span class="dashicons dashicons-' . $value['code'] . '"></span>';
            
        break;
        
        // Pixeden 7 Stroke
        case '7-stroke':
        
            // Return markup
            return '<i class="stroke-icon pe-7s-' . $value['code'] . '"></i>';
            
        break;
        
    }
    
    // Unknown type
    return null;
    
}

/**
 * Generate markup for provider-icon
 */
function provider_icon($provider)
{
    // Get icon from privcer
    $icon = $provider->icon;
    
    // Check what kind of icon
    switch($icon->type)
    {
        // Font Awesome
        case 'fa':
        
            // Return markup
            return '<i class="fa fa-' . $icon->content . '" title="' . $provider->label . '"></i>';
            
        break;
        
        // Image
        case 'img':
        
            // Get plugin URL
            $plugin_url = get_plugin_url();
            
            // Get image URL
            $image = $plugin_url . $icon->content;
            
            // Return markup
            return '<img class="provider-image" src="' . $image . '" alt="' . $provider->label . '" title="' . $provider->label . '">';
            
        break;
    }
    
    
}

/**
 * Get default field values
 */
function get_defaults()
{
    // Return default field values
    return array(
		'acf_ip_provider'         => array_keys(get_providers()),
        'acf_ip_placeholder_text' => __('Start to type...', 'acf-icon-picker'),
        'acf_ip_per_page'         => 12,
	);
}

/**
 * Get current plugin environment
 */
function get_plugin_environment()
{
    return $GLOBALS['acf_ip']['environment'];
}

/**
 * Get current plugin environment
 */
function get_plugin_version()
{
    return $GLOBALS['acf_ip']['version'];
}

/**
 * Get plugin url
 */
function get_plugin_url()
{
    return $GLOBALS['acf_ip']['plugin_url'];
}

/**
 * Get ACF version
 */
function get_acf_version()
{
    return $GLOBALS['acf_ip']['acf_version'];
}

/**
 * Get icon-providers from global array
 */
function get_providers($full = false)
{
    
    // Get provider-array
    $providers = $GLOBALS['acf_ip']['providers'];
    
    // Check if should return full array
    if($full)
    {
        // Return full
        return $providers;
    }
    
    // Format providers
    $providers = array_map(function($a){
        
        // Return only name as value
        return __($a->label, 'acf-icon-picker');
        
    }, $providers);
    
    // Return formatted providers
    return $providers;
    
}

/**
 * Include provider by provider object
 */
function include_provider($provider)
{
    
    // Get plugin directory URI
    $dir = get_plugin_url();
    
    // Determine inclusion-method
    switch($provider->inclusion)
    {
        // Native
        case 'native':
    
            // Enqueue native dependency
            wp_enqueue_style( $provider->dependency );
    
        break;
        
        // Inclusion via files
        case 'remote_files':
        case 'local_files':
    
            // Get files
            $files = $provider->files;
        
            // Loop through files
            foreach($files as $file)
            {
            
                // Check if should override
                if($file->override)
                {
                    // Deregister any other inclusions
                    wp_deregister_style( $file->name );
                }
                
                // Check if files are local
                if($provider->inclusion == 'local_files')
                {
                    // Add plugin url to file url
                    $file->url = $dir . $file->url;
                }
            
                // Register style
                wp_register_style(
                    $file->name,
                    $file->url,
                    $file->dependency,
                    $file->version,
                    $file->media
                );
            
                // Enqueue style
                wp_enqueue_style( $file->name );
            }
        
        break;
    }
}
