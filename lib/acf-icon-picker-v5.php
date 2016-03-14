<?php

if( ! defined( 'ABSPATH' ) ) exit;

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
		
		$this->name = 'icon_picker';
		
		
		/*
		*  label (string) Multiple words, can include spaces, visible when selecting a field type
		*/
		
		$this->label = __('Icon picker', 'acf-icon-picker');
		
		
		/*
		*  category (string) basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME
		*/
		
		$this->category = 'content';
		
		
		/*
		*  defaults (array) Array of default settings which are merged into the field object. These are used later in settings
		*/
		
		$this->defaults = array(
			'acf_ip_provider'         => array_keys($GLOBALS['acf_ic_providers']),
            'acf_ip_placeholder_text' => __('Start to type...', 'acf-icon-picker'),
            'acf_ip_per_page'         => 12,
		);
		
        
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
                $value = acf_ip_format_icon($value);
            }
            
            // Return
            return $value;
            
        }, 10, 3);
        
		// do not delete!
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
		
        // Get all and format providers
        $providers = array_map(function($a){
            
            // Return only name as value
            return __($a->label, 'acf-icon-picker');
            
        }, $GLOBALS['acf_ic_providers']);
        
        // Provider-field    
		acf_render_field_setting( $field, array(
			'label'        => __('Choose icon-provider', 'acf-icon-picker'),
            'instructions' => __('Are you missing a provider? Make a <a href="mailto:robert.sather@outlook.com">request</a> and maybe we can do something about it!','acf-icon-picker'),
			'type'         => 'checkbox',
			'name'         => 'acf_ip_provider',
			'layout'       => 'vertical',
            'choices'      => $providers
		));
        
        // Placeholder-field
		acf_render_field_setting( $field, array(
			'label'        => __('Placeholder text', 'acf-icon-picker'),
			'type'         => 'text',
            'instructions' => sprintf( __( 'Default: %s', 'acf-icon-picker' ), __('Start to type...', 'acf-icon-picker') ),
            'placeholder'  => __('Start to type...', 'acf-icon-picker'),
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
        
        // Check if placeholder is defined
        $placeholder = ( ( !isset($field['acf_ip_placeholder_text']) OR empty($field['acf_ip_placeholder_text']) ) ? __('Start to type...', 'acf-icon-picker') : $field['acf_ip_placeholder_text'] );
        
        // Check if placeholder is defined
        $number_of_hits = ( ( !isset($field['acf_ip_per_page']) OR empty($field['acf_ip_per_page']) ) ? $this->defaults['acf_ip_per_page'] : $field['acf_ip_per_page'] );
        
        // Get all providers
        $all_providers = $GLOBALS['acf_ic_providers'];
        
        // Get all active providers
        $active_providers = ( ( ! isset( $field['acf_ip_provider'] ) OR ! is_array( $field['acf_ip_provider'] ) ) ? array() : $field['acf_ip_provider'] );
        
        // Calculate diffs
        $diff = array_diff(array_keys($all_providers), $active_providers);
        
        // Check if we got diff
        $use_facets = (count($active_providers) > 0 AND is_array($diff) AND count($diff) > 0);
        
        // Get icon-markup
        $icon_value = acf_ip_format_icon( $field['value'], null, $field );
        
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
                    <a href="<?php echo $value->url; ?>"<?php if($type != $key) echo ' class="hidden"'; ?> data-provider="<?php echo $key; ?>" target="_blank"><i class="fa fa-<?php echo $value->icon; ?>"></i> <?php echo $value->label; ?></a>    
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Searchfield -->
            <input type="text" autocomplete="off" placeholder="<?php echo $placeholder; ?>" value="" class="acf-icon-autocomplete-me" id="acf-<?php echo esc_attr( $field['key'] ); ?>">
            
        </div>

		<?php
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
        		
        $dir = plugin_dir_url( __FILE__ ) . '../';
        
        // Register JS
        wp_register_script( 'algolia-autocomplete-jquery', "//cdn.jsdelivr.net/autocomplete.js/0/autocomplete.jquery.min.js" );
        wp_register_script( 'algolia-search', "//cdn.jsdelivr.net/algoliasearch/3/algoliasearch.min.js" );
        wp_register_script( 'acf-icon-picker', "{$dir}js/icon-picker.js" );
		
        // Override any other earlier inclusions
        wp_deregister_style( 'font-awesome' );
        
        // Register CSS
        wp_register_style( 'google-material-icons', "//fonts.googleapis.com/icon?family=Material+Icons" ); 
        wp_register_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css', array(), '4.5.0' );
		wp_register_style( 'acf-icon-picker', "{$dir}css/icon-picker.css" ); 
			
		// Scripts
		wp_enqueue_script(array(
            'algolia-autocomplete-jquery',
            'algolia-search',
            'acf-icon-picker',
		));

		// Styles
		wp_enqueue_style(array(
            'google-material-icons',
            'font-awesome',
			'acf-icon-picker',
		));

	}
	
}

new acf_field_icon_picker();