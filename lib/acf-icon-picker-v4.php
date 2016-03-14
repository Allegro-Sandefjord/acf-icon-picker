<?php

if( ! defined( 'ABSPATH' ) ) exit;

class acf_field_icon_picker extends acf_field {
	
	// vars
	var $settings, // will hold info such as dir / path
		$defaults; // will hold default field options
	
	/*
	*  __construct
	*
	*  Set name / label needed for actions / filters
	*
	*  @since	3.6
	*  @date	23/01/13
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
        
		$this->category = 'Content'; // Basic, Content, Choice, etc
        
        
		/*
		*  defaults (array) Array of default settings which are merged into the field object. These are used later in settings
		*/
        
		$this->defaults = array(
			'acf_ip_provider'         => array_keys($GLOBALS['acf_ic_providers']),
            'acf_ip_placeholder_text' => __('Start to type...', 'acf-icon-picker'),
            'acf_ip_per_page'         => 12,
		);
        
        
		/*
		*  Class settings
		*/
        
    	$this->settings = array(
			'path'    => apply_filters('acf/helpers/get_path', __FILE__),
			'dir'     => apply_filters('acf/helpers/get_dir', __FILE__),
			'version' => '2.0.0'
		);

        
		/*
		*  Custom formatting when printing out
		*/

        add_filter('acf/format_value_for_api', function($value, $post_id, $field){
            if(function_exists('acf_ip_format_icon') AND $field['type'] == 'icon_picker')
            {
                $value = acf_ip_format_icon($value);
            }
            return $value;
        }, 10, 3);

		// do not delete!
    	parent::__construct();
    	
	}
	
	
	/*
	*  create_options()
	*
	*  Create extra options for your field. This is rendered when editing a field.
	*  The value of $field['name'] can be used (like below) to save extra data to the $field
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field	- an array holding all the field's data
	*/
	
	function create_options( $field ) {
        
        // Merge defauls with field-variable
		$field = array_merge($this->defaults, $field);
        
        // Get field key
		$key = $field['name'];
        
        // Get all and format providers
        $providers = array_map(function($a){
            
            // Return only name as value
            return __($a->label, 'acf-icon-picker');
            
        }, $GLOBALS['acf_ic_providers']);
        
		?>

<tr class="field_option field_option_<?php echo $this->name; ?>">
	<td class="label">
		<label><?php _e('Choose icon-provider','acf-icon-picker'); ?></label>
        <?php printf( __('Are you missing a provider? Make a <a href="mailto:%s">request</a> and maybe we can do something about it!','acf-icon-picker'), 'robert.sather@outlook.com'); ?>
	</td>
	<td>
		<?php
		
        // Provider-field
		do_action('acf/create_field', array(
			'type'		   =>	'checkbox',
			'name'		   =>	'fields[' . $key . '][acf_ip_provider]',
			'value'	 	   =>	$field['acf_ip_provider'],
			'layout'	   =>	'vertical',
			'choices'	   =>	$providers
		));
		
		?>
	</td>
</tr>

<tr class="field_option field_option_<?php echo $this->name; ?>">
	<td class="label">
		<label><?php _e('Placeholder text', 'acf-icon-picker'); ?></label>
        <p class="description"><?php printf( __( 'Default: %s', 'acf-icon-picker' ), __('Start to type...', 'acf-icon-picker') ); ?></p>
	</td>
	<td>
		<?php
		
        // Placeholder-field
		do_action('acf/create_field', array(
			'type'		   =>	'text',
			'name'		   =>	'fields[' . $key . '][acf_ip_placeholder_text]',
			'value'		   =>	$field['acf_ip_placeholder_text'],
            'placeholder'  => __('Start to type...', 'acf-icon-picker'),
		));
		
		?>
	</td>
</tr>

<tr class="field_option field_option_<?php echo $this->name; ?>">
	<td class="label">
		<label><?php _e('Number of hits', 'acf-icon-picker'); ?></label>
        <p class="description"><?php printf( __( 'Default: %s', 'acf-icon-picker' ), $this->defaults['acf_ip_per_page'] ); ?></p>
	</td>
	<td>
		<?php
		
        // Number of hits-field
		do_action('acf/create_field', array(
			'type'		   =>	'number',
			'name'		   => 	'fields[' . $key . '][acf_ip_per_page]',
			'value'		   =>	$field['acf_ip_per_page'],
            'placeholder'  => $this->defaults['acf_ip_per_page'],
		));
		
		?>
	</td>
</tr>

		<?php	
	}
    
	/*
	*  create_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field - an array holding all the field's data
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/
    
	function create_field( $field )	{
        
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
        $icon_value = acf_ip_format_icon( $field['value'] );
        
        // Check if we got icon
        $has_icon = (!is_null($icon_value));
        
        // Get raw values
        $type = ( isset( $field['value']['type'] ) ? $field['value']['type'] : '' );
        $code = ( isset( $field['value']['code'] ) ? $field['value']['code'] : '' );
            
        ?>
        
        <div class="acf-<?php echo esc_attr($this->name); ?>">
        
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
        
        </div>

		<?php
        
	}
	
	/*
	*  input_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
	*  Use this action to add CSS + JavaScript to assist your create_field() action.
	*
	*  $info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
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