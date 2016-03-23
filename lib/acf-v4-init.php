<?php

if( ! defined( 'ABSPATH' ) ) exit;

/**
 * Initate ACF Icon Picker for ACF version 4
 *
 * @package WordPress
 * @subpackage ACF Icon Picker
 */

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
        
		$this->defaults = ACF_IP_helpers\get_defaults();
        
        
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
            
            // Check that helper-function is available and that the field type is icon_picker
            if(function_exists('ACF_IP_helpers\\acf_ip_format_icon') AND $field['type'] == 'icon_picker')
            {
                $value = ACF_IP_helpers\acf_ip_format_icon($value);
            }
            
            // Return value
            return $value;
            
        }, 10, 3);

		// Call parent constructor
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
        
        // Get all provider
        $providers = ACF_IP_helpers\get_providers();
        
		?>

<tr class="field_option field_option_<?php echo $this->name; ?>">
	<td class="label">
		<label><?php _e( 'Choose icon-provider','acf-icon-picker' ); ?></label>
        <?php printf( __( 'Are you missing a provider? Make a <a href="mailto:%s">request</a> and maybe we can do something about it!','acf-icon-picker'), $GLOBALS['acf_ip']['admin_email'] ); ?>
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
        
        // Render field
        ?>
        <!-- ACF v4 wrapper -->
        <div class="acf-<?php echo esc_attr($this->name); ?>">
            <?php ACF_IP_common\render_field($field, $this->defaults); ?>
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
        
        // Enqueue styles and scripts for Algolia and providers
        ACF_IP_common\admin_enqueue_scripts();
        
	}
	
}

new acf_field_icon_picker();