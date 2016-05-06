<?php

namespace ACF_IP_common;

if( ! defined( 'ABSPATH' ) ) exit;

/**
 * Common functions between ACF 4 and 5
 *
 * @package WordPress
 * @subpackage ACF Icon Picker
 */

/**
 * Render HTML for ACF field
 */
function render_field($field, $class_object)
{
    // Check if placeholder is defined
    $placeholder = ( ( !isset($field['acf_ip_placeholder_text']) OR empty($field['acf_ip_placeholder_text']) ) ? __('Start to type...', 'acf-icon-picker') : $field['acf_ip_placeholder_text'] );
    
    // Check if placeholder is defined
    $number_of_hits = ( ( !isset($field['acf_ip_per_page']) OR empty($field['acf_ip_per_page']) ) ? $class_object->defaults['acf_ip_per_page'] : $field['acf_ip_per_page'] );
    
    // Get all providers
    $all_providers = \ACF_IP_helpers\get_providers(true);
    
    // Get all active providers
    $active_providers = ( ( ! isset( $field['acf_ip_provider'] ) OR ! is_array( $field['acf_ip_provider'] ) ) ? array() : $field['acf_ip_provider'] );
    
    // Calculate diffs
    $diff = array_diff(array_keys($all_providers), $active_providers);
    
    // Check if we got diff
    $use_facets = (count($active_providers) > 0 AND is_array($diff) AND count($diff) > 0);
    
    // Get icon-markup
    $icon_value = \ACF_IP_helpers\format_icon( $field['value'] );
    
    // Check if we got icon
    $has_icon = (!is_null($icon_value));
    
    // Get raw values
    $type = ( isset( $field['value']['type'] ) ? $field['value']['type'] : '' );
    $code = ( isset( $field['value']['code'] ) ? $field['value']['code'] : '' );
        
    ?>
    
    <!-- ACF Icon Picker -->
	<div class="acf-input-wrap algolia-outer" data-hits-per-page="<?php echo $number_of_hits; ?>" data-use-facets="<?php echo ($use_facets ? 'true' : 'false'); ?>" data-active-provider="<?php echo implode(',', $active_providers); ?>">
        
        <!-- Hidden fields -->
        <input type="hidden" class="input-acf-ip-type" name="<?php echo esc_attr( $field['name'] ); ?>[type]" value="<?php echo esc_attr( $type ) ; ?>">
        <input type="hidden" class="input-acf-ip-code" name="<?php echo esc_attr( $field['name'] ); ?>[code]" value="<?php echo esc_attr( $code ) ; ?>">
        
        <!-- Icon container -->
        <div class="icon-container">
            
            <!-- Icon display -->
            <div class="icon-container-inner">
                <?php if($has_icon): ?>
                <?php echo $icon_value; ?>
                <?php else: ?>
                <i class="fa fa-question-circle empty-icon"></i>
                <?php endif; ?>
            </div>
            
            <!--  Clear-btn -->
            <a href="#" class="clear-icon-js<?php if(!$has_icon) echo ' hidden'; ?>">
                <span class="dashicons-before clear-icon dashicons-no-alt"></span>
            </a>
            
            <!-- Provider container -->
            <div class="provider">
                <?php foreach($all_providers as $key => $value): ?>
                <?php $provider_icon = \ACF_IP_helpers\provider_icon($value); ?>
                <a href="<?php echo $value->url; ?>"<?php if($type != $key) echo ' class="hidden"'; ?> data-provider="<?php echo $key; ?>" target="_blank"><?php echo $provider_icon; ?> <span class="inner"><?php echo $value->label; ?></span></a>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Searchfield -->
        <input type="text" autocomplete="off" placeholder="<?php echo $placeholder; ?>" value="" class="acf-icon-autocomplete-me" id="acf-<?php echo esc_attr( $field['key'] ); ?>">
        
    </div>
    
    <?php
    
}

/**
 * Enqueue styles and script for Algolia and providers
 */
function admin_enqueue_scripts()
{
    
    // Check if should use minified files
    $minified = (\ACF_IP_helpers\get_plugin_environment() == 'production');
    
    // Get plugin version
    $plugin_version = \ACF_IP_helpers\get_plugin_version();
    
    // Enqueue Aloglia JS
    wp_enqueue_script( 'algolia-autocomplete-jquery', "//cdn.jsdelivr.net/autocomplete.js/0/autocomplete.jquery.min.js", array('jquery'), '0.19.1', true );
    wp_enqueue_script( 'algolia-search', "//cdn.jsdelivr.net/algoliasearch/3/algoliasearch.min.js", array('jquery', 'algolia-autocomplete-jquery'), '3.14.0', true );
    
    // Get all providers    
    $providers = \ACF_IP_helpers\get_providers(true);
    
    // Loop through providers
    foreach($providers as $provider_key => $provider)
    {
        // Include provider
        \ACF_IP_helpers\include_provider($provider);
    }    
    
}