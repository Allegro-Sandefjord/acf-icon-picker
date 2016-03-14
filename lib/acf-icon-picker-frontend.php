<?php

// Add frontend-styles
add_action( 'wp_enqueue_scripts', function () {
    
    // Check if ACF 5
    if( function_exists('acf_add_local_field_group') )
    {
        $do_include = ( get_field( 'acf_ip_prevent_inclusion_css', 'option' ) !== true );
    }
    // Assume ACF 4, check our native WP option
    else
    {
        $do_include = ( get_option( 'acf_ip_prevent_inclusion_css' ) !== '1' );
    }
    
    // Check if should load frontend dependencies
    if( $do_include )
    {
        // Override any other earlier inclusions
        wp_deregister_style( 'font-awesome' );
    
        // Font Awesome
        wp_register_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css', array(), '4.5.0', 'all' ); 
        wp_enqueue_style( 'font-awesome' ); 
    
        // Google Material icons
        wp_register_style( 'google-material-icons', '//fonts.googleapis.com/icon?family=Material+Icons' ); 
        wp_enqueue_style( 'google-material-icons' );
        
    }
    
} );