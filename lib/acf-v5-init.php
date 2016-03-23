<?php

if( ! defined( 'ABSPATH' ) ) exit;

/**
 * Initate ACF Icon Picker for ACF version 5
 *
 * @package WordPress
 * @subpackage ACF Icon Picker
 */

class acf_field_icon_picker extends acf_field {

	/*
	*  __construct
	*
	*  This function will setup the field type data
	*
	*  @type	function
	*  @date	5/03/2014
	*  @since	5.0.0
	*
	*  @param	n/a
	*  @return	n/a
	*/
	
	function __construct() {
		
		/*
		*  name (string) Single word, no spaces. Underscores allowed
		*/
		
		$this->name     = 'icon_picker';
		
		
		/*
		*  label (string) Multiple words, can include spaces, visible when selecting a field type
		*/
		
		$this->label    = __('Icon picker', 'acf-icon-picker');
		
		
		/*
		*  category (string) basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME
		*/
		
		$this->category = 'content';
		
		
		/*
		*  defaults (array) Array of default settings which are merged into the field object. These are used later in settings
		*/
		
		$this->defaults = ACF_IP_helpers\get_defaults();
		
        
		/*
		*  Add custom validation to icon picker-field
		*/
		
        add_filter( "acf/validate_value/type=icon_picker", function($valid, $value, $field, $input){
            
            // Pass if not required
            if(!$field['required']) return true;
            
            // Check type
            if( ! isset($value['type']) OR empty($value['type']) ) return false;
            
            // Check code
            if( ! isset($value['code']) OR empty($value['code']) ) return false;
            
            // Passed validation
            return true;
            
        }, 10, 4);
        
        
		/*
		*  Add custom rendering
		*/
        
        add_filter( "acf/load_value/type=icon_picker", function( $value, $post_id, $field ) {
            
            // Check if not backend
            if(!is_admin())
            {
                // Format to icon
                $value = ACF_IP_helpers\format_icon($value);
            }
            
            // Return
            return $value;
            
        }, 10, 3);
        
		// Call parent constructor
    	parent::__construct();
    	
	}
	
	
	/*
	*  render_field_settings()
	*
	*  Create extra settings for your field. These are visible when editing a field
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/
	
	function render_field_settings( $field ) {
		
        // Get all provider
        $providers = ACF_IP_helpers\get_providers();
        
        // Provider-field    
		acf_render_field_setting( $field, array(
			'label'        => __('Choose icon-provider', 'acf-icon-picker'),
            'instructions' => sprintf( __('Are you missing a provider? Make a <a href="mailto:%s">request</a> and maybe we can do something about it!', 'acf-icon-picker'), $GLOBALS['acf_ip']['admin_email'] ),
			'type'         => 'checkbox',
			'name'         => 'acf_ip_provider',
			'layout'       => 'vertical',
            'choices'      => $providers
		));
        
        // Placeholder-field
		acf_render_field_setting( $field, array(
			'label'        => __('Placeholder text', 'acf-icon-picker'),
			'type'         => 'text',
            'instructions' => sprintf( __( 'Default: %s', 'acf-icon-picker' ), __( 'Start to type...', 'acf-icon-picker' ) ),
            'placeholder'  => __( 'Start to type...', 'acf-icon-picker' ),
			'name'         => 'acf_ip_placeholder_text',
		));
        
        // Number of hits-field
		acf_render_field_setting( $field, array(
			'label'        => __('Number of hits', 'acf-icon-picker'),
			'type'         => 'number',
            'instructions' => sprintf( __( 'Default: %s', 'acf-icon-picker' ), $this->defaults['acf_ip_per_page'] ),
			'name'         => 'acf_ip_per_page',
            'placeholder'  => $this->defaults['acf_ip_per_page'],
		));
        
	}
    
	/*
	*  render_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field (array) the $field being rendered
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/
	
	function render_field( $field ) {
        
        // Render field
        ACF_IP_common\render_field($field, $this);
        
	}
	
		
	/*
	*  input_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
	*  Use this action to add CSS + JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_enqueue_scripts)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/
	
	function input_admin_enqueue_scripts() {
        
        // Enqueue styles and scripts for Algolia and providers
        ACF_IP_common\admin_enqueue_scripts();

	}
	
}

new acf_field_icon_picker();