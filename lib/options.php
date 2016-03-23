<?php

if( ! defined( 'ABSPATH' ) ) exit;

/**
 * Initate ACF Icon Picker optionspage
 *
 * @package WordPress
 * @subpackage ACF Icon Picker
 */

class ACF_IP_options {
    
    /**
     * Native WP optionspage for ACF 4
     */
    public function init_v4()
    {
        
        // Add optionspage
        add_action('admin_menu', function(){
            if( function_exists('get_field') AND !function_exists('acf_add_options_sub_page') )
            {        
                add_options_page(
                    __('ACF Icon Picker', 'acf-icon-picker' ),
                    __( 'ACF Icon Picker', 'acf-icon-picker' ),
                    'manage_options',
                    'acf-ip-settings',
                    'acf_ip_settings'
                );
            }
        });

        // Form setup
        function acf_ip_settings()
        {
        	?>
        	<div class="wrap">
                <h2><?php _e('ACF Icon Picker', 'acf-icon-picker'); ?></h2>
        		<form action="options.php" method="post" name="acf-v4-acf-ip-optionspage">
        			<?php settings_fields('acf_ip'); ?>
        			<table class="form-table">
        				<tr>
        					<th scope="row">
                                <label for="acf_ip_prevent_inclusion_css"><?php _e( 'Custom inclusion of CSS front-end dependencies?', 'acf-icon-picker' ); ?></label>
                                <p class="description"><?php _e( '(If this is not checked then all dependencies will be loaded.)', 'acf-icon-picker' ); ?></p>
                            </th>
        					<td><input type="checkbox" value="1" id="acf_ip_prevent_inclusion_css" name="acf_ip_prevent_inclusion_css" <?php checked(get_option('acf_ip_prevent_inclusion_css')); ?>></td>
        				</tr>
        				<tr id="acf_ip_custom_inclusion-tab"<?php if(!get_option('acf_ip_prevent_inclusion_css')) echo ' style="display:none;"'?>>
        					<th scope="row">
                                <label for="acf_ip_prevent_inclusion_css"><?php _e( 'Custom inclusion', 'acf-icon-picker' ); ?></label>
                                <p class="description"><?php _e( '(Choose the dependencies that should be loaded during a front-end pageload.)', 'acf-icon-picker' ); ?></p>
                            </th>
        					<td>
                                <?php $providers = ACF_IP_helpers\get_providers(true); ?>
                                <?php $custom_inclusion = get_option('acf_ip_custom_inclusion'); ?>
                                <?php $all_selected = ($custom_inclusion === false); ?>
                                <?php foreach($providers as $key => $provider): ?>
                                <?php $value = (isset($custom_inclusion[$key]) AND $custom_inclusion[$key] == '1'); ?>
                                <input type="checkbox" value="1" id="acf_ip_custom_inclusion-<?php echo $key; ?>" name="acf_ip_custom_inclusion[<?php echo $key; ?>]" <?php checked(($all_selected OR $value)); ?>>
                                <label for="acf_ip_custom_inclusion-<?php echo $key; ?>"><?php echo $provider->label; ?></label><br>
                                <?php endforeach; ?>
                            </td>
        				</tr>
        			</table>
            
                    <p class="description"><?php _e( 'Note that inclusion of many files may increase the loading time of your page.<br>The custom inclusion setting can be handy if you want to control the front-end dependencies yourself.', 'acf-icon-picker' ); ?></p>
            
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
                register_setting('acf_ip', 'acf_ip_custom_inclusion');
            }
        });
        
    }
    
    /**
     * Optionspage for ACF 5
     */
    public function init_v5()
    {
        
        add_action('wp_loaded', function(){ 
    
            // Add optionspage
            if( function_exists('acf_add_options_sub_page') ) {
            	acf_add_options_sub_page(array(
            		'page_title' 	=> __('ACF Icon Picker', 'acf-icon-picker'),
            		'menu_title' 	=> __('ACF Icon Picker', 'acf-icon-picker'),
                    'parent_slug' 	=> 'options-general.php',
                    'menu_slug'     => 'acf-options-acf-icon-picker',
            	));
            }
    
            // Add optionsfield
            if( function_exists('acf_add_local_field_group') )
            {
        
                // Get providers
                $providers = ACF_IP_helpers\get_providers();
        
                // Create fields
                $fields = array (
            		array (
            			'key' => 'field_acf_ip_56e5eec8f5213',
            			'label' => __( 'Custom inclusion of CSS front-end dependencies?', 'acf-icon-picker' ),
            			'name' => 'acf_ip_prevent_inclusion_css',
            			'type' => 'true_false',
            			'instructions' => __( '(If this is not checked then all dependencies will be loaded.)', 'acf-icon-picker' ),
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
                    array (
            			'key' => 'field_acf_ip_56ec5e4fd5f89',
            			'label' => __( 'Custom inclusion', 'acf-icon-picker' ),
            			'name' => 'acf_ip_custom_inclusion',
            			'type' => 'checkbox',
            			'instructions' => __( '(Choose the dependencies that should be loaded during a front-end pageload.)', 'acf-icon-picker' ),
            			'required' => 0,
            			'conditional_logic' => array (
            				array (
            					array (
            						'field' => 'field_acf_ip_56e5eec8f5213',
            						'operator' => '==',
            						'value' => '1',
            					),
            				),
            			),
            			'wrapper' => array (
            				'width' => '',
            				'class' => '',
            				'id' => '',
            			),
            			'choices' => $providers,
            			'default_value' => array_keys($providers),
            			'layout' => 'vertical',
            			'toggle' => 0,
            		),
                    array (
            			'key' => 'field_acf_ip_56eea32c3f449',
            			'label' => __( 'Note:', 'acf-icon-picker' ),
            			'name' => 'acf_ip_option_note',
            			'type' => 'message',
            			'instructions' => '',
            			'required' => 0,
            			'conditional_logic' => 0,
            			'wrapper' => array (
            				'width' => '',
            				'class' => '',
            				'id' => '',
            			),
            			'message' => __( 'Note that inclusion of many files may increase the loading time of your page.<br>The custom inclusion setting can be handy if you want to control the front-end dependencies yourself.', 'acf-icon-picker' ),
            			'new_lines' => 'wpautop',
            			'esc_html' => 0,
            		)
                );
        
                // Check if should do an icon check
                if(isset($_GET['icon-check']))
                {
                    $fields[] = array (
            			'key' => 'field_acf_ip_56elo09df3829',
            			'label' => 'Icon check',
            			'name' => 'acf_ip_icon_check',
            			'type' => 'message',
            			'instructions' => '',
            			'required' => 0,
            			'conditional_logic' => 0,
            			'wrapper' => array (
            				'width' => '',
            				'class' => '',
            				'id' => '',
            			),
            			'message' => '',
            			'new_lines' => 'wpautop',
            			'esc_html' => 0,
            		);
                }
        
                acf_add_local_field_group(array (
                	'key' => 'group_acf_ip_56e5eeb4c31c7',
                	'title' => __( 'Settings', 'acf-icon-picker' ),
                	'fields' => $fields,
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
        
    }
    
}