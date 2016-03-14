<?php

/*
*  Native WP optionspage for ACF 4
*/

// Add optionspage
add_action('admin_menu', function(){
    if( function_exists('get_field') AND !function_exists('acf_add_options_sub_page') )
    {        
        add_options_page( 'ACF Icon Picker', 'ACF Icon Picker', 'manage_options', 'acf-ip-settings', 'acf_ip_settings');
    }
});

// Form setup
function acf_ip_settings()
{
	?>
	<div class="wrap">
        <h2><?php _e('ACF Icon Picker', 'acf-icon-picker'); ?></h2>
		<form action="options.php" method="post">
			<?php settings_fields('acf_ip'); ?>
			<table class="form-table">
				<tr>
					<th scope="row">
                        <label for="acf_ip_prevent_inclusion_css">Prevent inclusion of CSS front-end dependencies?</label>
                        <p class="description">(Google Material icons, Font Awesome etc.)</p>
                    </th>
					<td><input type="checkbox" value="1" id="acf_ip_prevent_inclusion_css" name="acf_ip_prevent_inclusion_css" <?php checked(get_option('acf_ip_prevent_inclusion_css')); ?>></td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}

// Register setting
add_action('admin_init', function(){
    if( function_exists('get_field') AND !function_exists('acf_add_options_sub_page') )
    {
        register_setting('acf_ip', 'acf_ip_prevent_inclusion_css');
    }
});

/*
*  Optionspage for ACF 5
*/

add_action('wp_loaded', function(){ 
    
    // Add optionspage
    if( function_exists('acf_add_options_sub_page') ) {
    	acf_add_options_sub_page(array(
    		'page_title' 	=> __('ACF Icon Picker', 'acf-icon-picker'),
    		'menu_title' 	=> __('ACF Icon Picker', 'acf-icon-picker'),
            'parent_slug' 	=> 'options-general.php'
    	));
    }
    
    // Add optionsfield
    if( function_exists('acf_add_local_field_group') )
    {
        acf_add_local_field_group(array (
        	'key' => 'group_acf_ic_56e5eeb4c31c7',
        	'title' => 'Settings',
        	'fields' => array (
        		array (
        			'key' => 'field_acf_ic_56e5eec8f5213',
        			'label' => 'Prevent inclusion of CSS front-end dependencies?',
        			'name' => 'acf_ip_prevent_inclusion_css',
        			'type' => 'true_false',
        			'instructions' => '(Google Material icons, Font Awesome etc.)',
        			'required' => 0,
        			'conditional_logic' => 0,
        			'wrapper' => array (
        				'width' => '',
        				'class' => '',
        				'id' => '',
        			),
        			'message' => '',
        			'default_value' => 0,
        		),
        	),
        	'location' => array (
        		array (
        			array (
        				'param' => 'options_page',
        				'operator' => '==',
        				'value' => 'acf-options-acf-icon-picker',
        			),
        		),
        	),
        	'menu_order' => 0,
        	'position' => 'normal',
        	'style' => 'default',
        	'label_placement' => 'top',
        	'instruction_placement' => 'label',
        	'hide_on_screen' => '',
        	'active' => 1,
        	'description' => '',
        ));
    }
    
});