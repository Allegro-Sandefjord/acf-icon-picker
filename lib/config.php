<?php

if( ! defined( 'ABSPATH' ) ) exit;

/**
 * Set ACF Icon Picker config
 */

// Set admin email
$GLOBALS['acf_ip']['admin_email'] = 'robert.sather@outlook.com';

// Set plugin environment
$GLOBALS['acf_ip']['environment'] = ( defined( 'ACF_IP_ENVIRONMENT' ) ? ACF_IP_ENVIRONMENT : 'production' );

// Get plugin directory URI
$GLOBALS['acf_ip']['plugin_url'] = plugin_dir_url( dirname(__DIR__) . '/acf-icon-picker.php' );

// Set plugin version
$GLOBALS['acf_ip']['version'] = '1.3';

// Define icon-providers
$GLOBALS['acf_ip']['providers'] = array(
    
    // Google Material Icons
    'gmi' => (object) array(
        'label'     => 'Google Material icons',
        'url'       => 'https://design.google.com/icons/',
        'icon'      => (object) array(
            'type'    => 'fa',
            'content' => 'google'
        ),
        'inclusion' => 'remote_files',
        'files'     => array(
            (object) array(
                'name'       => 'google-material-icons',
                'url'        => '//fonts.googleapis.com/icon?family=Material+Icons',
                'dependency' => array(),
                'version'    => false,
                'media'      => 'all',
                'override'   => false
            ),
        )
    ),
    
    // Font Awesome
    'fa' => (object) array(
        'label'     => 'Font Awesome',
        'url'       => 'http://fontawesome.io/',
        'icon'      => (object) array(
            'type'    => 'fa',
            'content' => 'flag'
        ),
        'inclusion' => 'remote_files',
        'files'     => array(
            (object) array(
                'name'       => 'font-awesome',
                'url'        => '//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css',
                'dependency' => array(),
                'version'    => '4.5.0',
                'media'      => 'all',
                'override'   => true
            ),
        )
    ),
    
    // WordPress Dashicons
    'wp' => (object) array(
        'label'      => 'WP Dashicons',
        'url'        => 'https://developer.wordpress.org/resource/dashicons/',
        'icon'      => (object) array(
            'type'    => 'fa',
            'content' => 'wordpress'
        ),
        'inclusion'  => 'native',
        'dependency' => 'dashicons',
    ),
    
    // Pixeden 7 Stroke
    '7-stroke' => (object) array(
        'label' => 'Pixeden 7 Stroke',
        'url'   => 'http://themes-pixeden.com/font-demos/7-stroke/',
        'icon'      => (object) array(
            'type'    => 'img',
            'content' => 'providers/7-stroke/pixeden-logo.png'
        ),
        'inclusion' => 'local_files',
        'files'     => array(
            (object) array(
                'name'       => '7-stroke',
                'url'        => 'providers/7-stroke/css/pe-icon-7-stroke.min.css',
                'dependency' => array(),
                'version'    => '1.2.0',
                'media'      => 'all',
                'override'   => true
            ),
            (object) array(
                'name'       => '7-stroke-helper',
                'url'        => 'providers/7-stroke/css/helper.min.css',
                'dependency' => array(),
                'version'    => '1.2.0',
                'media'      => 'all',
                'override'   => true
            ),
        )
    ),
    
);