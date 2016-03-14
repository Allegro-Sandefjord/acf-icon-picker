<?php

function acf_ip_format_icon( $value ) {
    
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
    }
    
    // Unknown type
    return null;
    
}