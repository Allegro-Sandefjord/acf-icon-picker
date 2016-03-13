<?php

if( ! defined( 'ABSPATH' ) ) exit;

class acf_field_icon_picker extends acf_field {
	
	// vars
	var $settings, // will hold info such as dir / path
		$defaults; // will hold default field options
	
	private static $YOUTUBE_PARAMS = array( 
		'channelId', 'channelType', 'eventType', 'order', 'regionCode', 
		'safeSearch', 'topicId', 'videoCaption', 'videoCategoryId', 'videoDefinition', 
		'videoDimension', 'videoDuration', 'videoEmbeddable', 'videoLicense', 
		'videoSyndicated', 'videoType', 'maxResults', 'relatedVideoId', 'relevanceLanguage' 
	);

	/*
	*  __construct
	*
	*  Set name / label needed for actions / filters
	*
	*  @since	3.6
	*  @date	23/01/13
	*/
	
	function __construct() {
		$this->name     = 'icon_picker';
		$this->label    = __('Icon Picker');
		$this->category = __("jQuery",'acf'); // Basic, Content, Choice, etc
		$this->defaults = array(
			'api_key'           => 'AIzaSyAuHQVhEmD4m2AXL6TvADwZIxZjNogVRF0',
			'multiple'          => false,
			'channelType'       => '',
			'order'             => 'relevance',
			'safeSearch'        => 'none',
			'videoCaption'      => 'any',
			'videoDefinition'   => 'any',
			'videoDimension'    => 'any',
			'videoDuration'     => 'any',
			'videoEmbeddable'   => 'true',
			'videoLicense'      => 'any',
			'videoSyndicated'   => 'any',
			'videoType'         => 'any',
			'channelId'         => '',
			'eventType'         => '',
			'regionCode'        => '',
			'topicId'           => '',
			'videoCategoryId'   => '',
			'maxResults'        => '',
			'relatedVideoId'    => '',
			'relevanceLanguage' => '',
			'answerOptions'     => array( 'vid', 'title', 'thumbs', 'iframe', 'url' ),
		);

		// do not delete!
    	parent::__construct();
    	
    	$this->settings = array(
			'path'    => apply_filters('acf/helpers/get_path', __FILE__),
			'dir'     => apply_filters('acf/helpers/get_dir', __FILE__),
			'version' => '2.0.0'
		);
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
		$field = array_merge($this->defaults, $field);
		$key = $field['name'];
		
		?>
<tr class="field_option field_option_<?php echo $this->name; ?>">
	<td class="label">
		<label><?php _e("API KEY - YouTube Data API",'acf-icon-picker'); ?></label>
		<p class="description"><?php echo sprintf( __('<a href="%s" target="_blank">click here</a> for you know how to obtain the api key','acf'), 'https://developers.google.com/youtube/v3/getting-started' ); ?></p>
	</td>
	<td>
		<?php
		
		do_action('acf/create_field', array(
			'type'   =>	'text',
			'name'   =>	'fields['.$key.'][api_key]',
			'value'  =>	$field['api_key']
		));
		
		?>
	</td>
</tr>

<tr class="field_option field_option_<?php echo $this->name; ?>">
	<td class="label">
		<label><?php _e("Select multiple videos?",'acf-icon-picker'); ?></label>
	</td>
	<td>
		<?php
		
		do_action('acf/create_field', array(
			'type'		=>	'radio',
			'name'		=>	'fields['.$key.'][multiple]',
			'value'		=>	$field['multiple'],
			'layout'	=>	'horizontal',
			'choices'	=>	array(
				1 => __('Yes', 'acf-icon-picker'),
				0 => __('No', 'acf-icon-picker'),
			)
		));
		
		?>
	</td>
</tr>

<tr class="acf-field field_type-radio" data-name="yp_advanced_options" data-type="radio" data-setting="icon-picker">
	<td class="label">
		<label>Advanced Options</label>
		<p class="description"><?php _e("Set advanced options for Icon Picker.", 'acf-icon-picker'); ?></p>
	</td>
	<td>
		<?php
		
		do_action('acf/create_field', array(
			'type'    =>	'radio',
			'name'    =>	'fields['.$key.'][yp_advanced_options]',
			'value'   =>	0,
			'layout'  =>	'horizontal',
			'class'   => 'yp-advanced-options',
			'choices' =>	array(
				1 => __('Show', 'acf-icon-picker'),
				0 => __('Hide', 'acf-icon-picker'),
			)
		));
		
		?>
	</td>
</tr>

<tr class="field_option field_option_<?php echo $this->name; ?> field_advanced">
	<td class="label">
		<label><?php _e("Answer Options",'acf-icon-picker'); ?></label>
	</td>
	<td>
		<?php
		
		do_action('acf/create_field', array(
			'type'		=>	'checkbox',
			'name'		=>	'fields['.$key.'][answerOptions]',
			'value'		=>	$field['answerOptions'],
			'layout'	=>	'horizontal',
			'choices'	=>	array(
				'title'    => __('Title', 'acf-icon-picker'),
				'vid'      => __('Video ID', 'acf-icon-picker'),
				'url'      => __('Video URL', 'acf-icon-picker'),
				'thumbs'   => __('Video thumbnails', 'acf-icon-picker'),
				'duration' => __('Duration', 'acf-icon-picker'),
				'iframe'   => __('Embed Code', 'acf-icon-picker'),
			),
		));
		
		?>
	</td>
</tr>

<tr class="field_option field_option_<?php echo $this->name; ?> field_advanced">
	<td class="label">
		<label><?php _e("Channel ID",'acf-icon-picker'); ?></label>
	</td>
	<td>
		<?php
		
		do_action('acf/create_field', array(
			'type'		=>	'text',
			'name'		=>	'fields['.$key.'][channelId]',
			'value'		=>	$field['channelId'],
			'layout'	=>	'horizontal',
		));
		
		?>
	</td>
</tr>

<tr class="field_option field_option_<?php echo $this->name; ?> field_advanced">
	<td class="label">
		<label><?php _e("Channel Type",'acf-icon-picker'); ?></label>
	</td>
	<td>
		<?php
		
		do_action('acf/create_field', array(
			'type'		=>	'radio',
			'name'		=>	'fields['.$key.'][channelType]',
			'value'		=>	$field['channelType'],
			'layout'	=>	'horizontal',
			'choices'	=>	array(
				''  => __('Any', 'acf-icon-picker'),
				'show' => __('Show', 'acf-icon-picker'),
			)
		));
		
		?>
	</td>
</tr>

<tr class="field_option field_option_<?php echo $this->name; ?> field_advanced">
	<td class="label">
		<label><?php _e("Event Type",'acf-icon-picker'); ?></label>
	</td>
	<td>
		<?php
		
		do_action('acf/create_field', array(
			'type'		=>	'select',
			'name'		=>	'fields['.$key.'][eventType]',
			'value'		=>	$field['eventType'],
			'layout'	=>	'horizontal',
			'choices'	=>	array(
				''          => __('-- choose --', 'acf-icon-picker'),
				'completed' => __('Completed', 'acf-icon-picker'),
				'live'      => __('Live', 'acf-icon-picker'),
				'upcoming'  => __('Upcoming', 'acf-icon-picker'),
			)
		));
		
		?>
	</td>
</tr>

<tr class="field_option field_option_<?php echo $this->name; ?> field_advanced">
	<td class="label">
		<label><?php _e("Order",'acf-icon-picker'); ?></label>
	</td>
	<td>
		<?php
		
		do_action('acf/create_field', array(
			'type'		=>	'radio',
			'name'		=>	'fields['.$key.'][order]',
			'value'		=>	$field['order'],
			'layout'	=>	'horizontal',
			'choices'	=>	array(
				'date'       => __('Date', 'acf-icon-picker'),
				'rating'     => __('Rating', 'acf-icon-picker'),
				'relevance'  => __('Relevance', 'acf-icon-picker'),
				'title'      => __('Title', 'acf-icon-picker'),
				'videoCount' => __('Video Count', 'acf-icon-picker'),
				'viewCount'  => __('View Count', 'acf-icon-picker'),
			)
		));
		
		?>
	</td>
</tr>

<tr class="field_option field_option_<?php echo $this->name; ?> field_advanced">
	<td class="label">
		<label><?php _e("Safe Search",'acf-icon-picker'); ?></label>
	</td>
	<td>
		<?php
		
		do_action('acf/create_field', array(
			'type'		=>	'radio',
			'name'		=>	'fields['.$key.'][safeSearch]',
			'value'		=>	$field['safeSearch'],
			'layout'	=>	'horizontal',
			'choices'	=>	array(
				'moderate' => __('Moderate', 'acf-icon-picker'), 
				'none'     => __('None', 'acf-icon-picker'), 
				'strict'   => __('Strict', 'acf-icon-picker')
			)
		));
		
		?>
	</td>
</tr>

<tr class="field_option field_option_<?php echo $this->name; ?> field_advanced">
	<td class="label">
		<label><?php _e("Video Caption",'acf-icon-picker'); ?></label>
	</td>
	<td>
		<?php
		
		do_action('acf/create_field', array(
			'type'		=>	'radio',
			'name'		=>	'fields['.$key.'][videoCaption]',
			'value'		=>	$field['videoCaption'],
			'layout'	=>	'horizontal',
			'choices'	=>	array(
				'any'           => __('Any', 'acf-icon-picker'), 
				'closedCaption' => __('Closed Caption', 'acf-icon-picker'), 
				'none'          => __('None', 'acf-icon-picker'),
			)
		));
		
		?>
	</td>
</tr>

<tr class="field_option field_option_<?php echo $this->name; ?> field_advanced">
	<td class="label">
		<label><?php _e("Video Definition",'acf-icon-picker'); ?></label>
	</td>
	<td>
		<?php
		
		do_action('acf/create_field', array(
			'type'		=>	'radio',
			'name'		=>	'fields['.$key.'][videoDefinition]',
			'value'		=>	$field['videoDefinition'],
			'layout'	=>	'horizontal',
			'choices'	=>	array(
				'any'      => __('Any', 'acf-icon-picker'), 
				'high'     => __('High', 'acf-icon-picker'), 
				'standard' => __('Standard', 'acf-icon-picker'),
			)
		));
		
		?>
	</td>
</tr>

<tr class="field_option field_option_<?php echo $this->name; ?> field_advanced">
	<td class="label">
		<label><?php _e("Video Dimension",'acf-icon-picker'); ?></label>
	</td>
	<td>
		<?php
		
		do_action('acf/create_field', array(
			'type'		=>	'radio',
			'name'		=>	'fields['.$key.'][videoDimension]',
			'value'		=>	$field['videoDimension'],
			'layout'	=>	'horizontal',
			'choices'	=>	array(
				'any' => __('Any', 'acf-icon-picker'), 
				'2d'  => __('2d', 'acf-icon-picker'), 
				'3d'  => __('3d', 'acf-icon-picker'),
			)
		));
		
		?>
	</td>
</tr>

<tr class="field_option field_option_<?php echo $this->name; ?> field_advanced">
	<td class="label">
		<label><?php _e("Video Duration",'acf-icon-picker'); ?></label>
	</td>
	<td>
		<?php
		
		do_action('acf/create_field', array(
			'type'		=>	'radio',
			'name'		=>	'fields['.$key.'][videoDuration]',
			'value'		=>	$field['videoDuration'],
			'layout'	=>	'horizontal',
			'choices'	=>	array(
				'any'    => __('Any', 'acf-icon-picker'), 
				'long'   => __('long', 'acf-icon-picker'), 
				'medium' => __('medium', 'acf-icon-picker'),
				'short'  => __('short', 'acf-icon-picker'),
			)
		));
		
		?>
	</td>
</tr>

<tr class="field_option field_option_<?php echo $this->name; ?> field_advanced">
	<td class="label">
		<label><?php _e("Video Embeddable",'acf-icon-picker'); ?></label>
	</td>
	<td>
		<?php
		
		do_action('acf/create_field', array(
			'type'		=>	'radio',
			'name'		=>	'fields['.$key.'][videoEmbeddable]',
			'value'		=>	$field['videoEmbeddable'],
			'layout'	=>	'horizontal',
			'choices'	=>	array(
				'true' => __('Yes', 'acf-icon-picker'), 
				'any'  => __('No', 'acf-icon-picker'),
			)
		));
		
		?>
	</td>
</tr>

<tr class="field_option field_option_<?php echo $this->name; ?> field_advanced">
	<td class="label">
		<label><?php _e("Video License",'acf-icon-picker'); ?></label>
	</td>
	<td>
		<?php
		
		do_action('acf/create_field', array(
			'type'		=>	'radio',
			'name'		=>	'fields['.$key.'][videoLicense]',
			'value'		=>	$field['videoLicense'],
			'layout'	=>	'horizontal',
			'choices'	=>	array(
				'any'            => __('Any', 'acf-icon-picker'),
				'creativeCommon' => __('Creative Common', 'acf-icon-picker'), 
				'youtube'        => __('YouTube', 'acf-icon-picker'), 
			)
		));
		
		?>
	</td>
</tr>

<tr class="field_option field_option_<?php echo $this->name; ?> field_advanced">
	<td class="label">
		<label><?php _e("Video Syndicated",'acf-icon-picker'); ?></label>
		<p class="description"><?php echo _e('The Video Syndicated lets you to restrict a search to only videos that can be played outside youtube.com', 'acf'); ?></p>
	</td>
	<td>
		<?php
		
		do_action('acf/create_field', array(
			'type'		=>	'radio',
			'name'		=>	'fields['.$key.'][videoSyndicated]',
			'value'		=>	$field['videoSyndicated'],
			'layout'	=>	'horizontal',
			'choices'	=>	array(
				'true' => __('Yes', 'acf-icon-picker'),
				'any'  => __('No', 'acf-icon-picker'),
			)
		));
		
		?>
	</td>
</tr>

<tr class="field_option field_option_<?php echo $this->name; ?> field_advanced">
	<td class="label">
		<label><?php _e("Video Type",'acf-icon-picker'); ?></label>
	</td>
	<td>
		<?php
		
		do_action('acf/create_field', array(
			'type'		=>	'radio',
			'name'		=>	'fields['.$key.'][videoType]',
			'value'		=>	$field['videoType'],
			'layout'	=>	'horizontal',
			'choices'	=>	array(
				'any'     => __('Any', 'acf-icon-picker'),
				'episode' => __('Episode', 'acf-icon-picker'),
				'movie'   => __('Movie', 'acf-icon-picker'),
			)
		));
		
		?>
	</td>
</tr>

<tr class="field_option field_option_<?php echo $this->name; ?> field_advanced">
	<td class="label">
		<label><?php _e("Video Category ID",'acf-icon-picker'); ?></label>
	</td>
	<td>
		<?php
		
		do_action('acf/create_field', array(
			'type'		=>	'text',
			'name'		=>	'fields['.$key.'][videoCategoryId]',
			'value'		=>	$field['videoCategoryId'],
			'layout'	=>	'horizontal',
		));
		
		?>
	</td>
</tr>

<tr class="field_option field_option_<?php echo $this->name; ?> field_advanced">
	<td class="label">
		<label><?php _e("Related Video ID",'acf-icon-picker'); ?></label>
	</td>
	<td>
		<?php
		
		do_action('acf/create_field', array(
			'type'		=>	'text',
			'name'		=>	'fields['.$key.'][relatedVideoId]',
			'value'		=>	$field['relatedVideoId'],
			'layout'	=>	'horizontal',
		));
		
		?>
	</td>
</tr>

<tr class="field_option field_option_<?php echo $this->name; ?> field_advanced">
	<td class="label">
		<label><?php _e("Topic ID",'acf-icon-picker'); ?></label>
		<p class="description"><?php _e('The value identifies a Freebase topic ID.', 'acf'); ?></p>
	</td>
	<td>
		<?php
		
		do_action('acf/create_field', array(
			'type'		=>	'text',
			'name'		=>	'fields['.$key.'][topicId]',
			'value'		=>	$field['topicId'],
			'layout'	=>	'horizontal',
		));
		
		?>
	</td>
</tr>

<tr class="field_option field_option_<?php echo $this->name; ?> field_advanced">
	<td class="label">
		<label><?php _e("Region Code",'acf-icon-picker'); ?></label>
		<p class="description"><?php echo sprintf(__('The field value is an <a href="%s" target="_blank">ISO 3166-1 alpha-2</a> country code.', 'acf'), 'http://www.iso.org/iso/country_codes/iso_3166_code_lists/country_names_and_code_elements.htm'); ?></p>
	</td>
	<td>
		<?php
		
		do_action('acf/create_field', array(
			'type'		=>	'text',
			'name'		=>	'fields['.$key.'][regionCode]',
			'value'		=>	$field['regionCode'],
			'layout'	=>	'horizontal',
		));
		
		?>
	</td>
</tr>

<tr class="field_option field_option_<?php echo $this->name; ?> field_advanced">
	<td class="label">
		<label><?php _e("Relevance Language",'acf-icon-picker'); ?></label>
		<p class="description"><?php _e('The field value is typically an ISO 639-1 two-letter language code.', 'acf'); ?></p>
	</td>
	<td>
		<?php
		
		do_action('acf/create_field', array(
			'type'		=>	'text',
			'name'		=>	'fields['.$key.'][relevanceLanguage]',
			'value'		=>	$field['relevanceLanguage'],
			'layout'	=>	'horizontal',
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
		$field = array_merge($this->defaults, $field);
		
		if(!array_key_exists('api_key', $field) || empty($field['api_key'])){
		?>
			<p><?php _e('Please, set your api key.', 'acf'); ?></p>
		<?php
			return;
		}

		$options = array();
		
		foreach( self::$YOUTUBE_PARAMS as $p ) {
			if( array_key_exists($p, $field ) ) {
				$options[$p] = esc_js( $field[$p] );
			}
		}
		
		$options['type'] = 'video';
		
		?>
		<input type="text" id="<?php echo esc_attr( $field['id'] ); ?>" name="<?php echo esc_attr( $field['name'] ); ?>" data-name="<?php echo esc_attr( $field['name'] ); ?>" class="acf-<?php echo esc_attr( $this->name ); ?>-field" data-multiple="<?php echo esc_attr( $field['multiple'] ); ?>" data-api-key="<?php echo $field['api_key']; ?>" data-options="<?php echo esc_attr( json_encode( $options ) ); ?>">
		<div class="acf-<?php echo esc_attr($this->name); ?>">
			<div id="<?php echo esc_attr($field['id']); ?>-holder" class="thumbnails<?php echo ($field['multiple'] ? ' multiple' : ''); ?>">
				<div class="inner clearfix ui-sortable">
		<?php
		if($field['value']):
			foreach($field['value'] as $v): ?>
				<div class="thumbnail">
					<input type="hidden" name="<?php echo esc_attr($field['name']); ?>[]" value="<?php echo esc_attr(json_encode($v)); ?>">
					<div class="inner clearfix">
						<img src="http://i.ytimg.com/vi/<?php echo esc_attr($v['vid']); ?>/default.jpg">
					</div>
					<div class="hover">
						<ul class="bl">
							<li><a href="#" class="acf-button-delete ir">remove</a></li>
						</ul>
					</div>
				</div>
		<?php 
			endforeach; 
		endif; ?>
				</div>
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
		wp_register_script( 'acf-nanoscroller', $this->settings['dir'] . 'js/nanoscroller.js', array('acf-input'), $this->settings['version'] );
		wp_register_script( 'acf-icon-picker', $this->settings['dir'] . 'js/icon-picker.js', array('acf-input'), $this->settings['version'] );
		wp_register_script( 'acf-input-icon-picker', $this->settings['dir'] . 'js/input.js', array('acf-input'), $this->settings['version'] );
		
		wp_register_style( 'acf-icon-picker', $this->settings['dir'] . 'css/icon-picker.css', array('acf-input'), $this->settings['version'] ); 
		wp_register_style( 'acf-nanoscroller', $this->settings['dir'] . 'css/nanoscroller.css', array('acf-input'), $this->settings['version'] ); 
		wp_register_style( 'acf-input-icon-picker', $this->settings['dir'] . 'css/input.css', array('acf-input'), $this->settings['version'] );
			
		wp_enqueue_script(array(
			'acf-nanoscroller',	
			'acf-icon-picker',	
			'acf-input-icon-picker',	
			'jquery-ui-sortable',
		));

		wp_enqueue_style(array(
			'acf-icon-picker',	
			'acf-nanoscroller',	
			'acf-input-icon-picker',	
		));
	}
	
	/*
	*  field_group_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is edited.
	*  Use this action to add CSS + JavaScript to assist your create_field_options() action.
	*
	*  $info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/

	function field_group_admin_enqueue_scripts() {
		wp_register_script( 'acf-field-group-icon-picker', $this->settings['dir'] . 'js/field-group.js', array('acf-field-group'), $this->settings['version'] );
		wp_register_style( 'acf-field-group-icon-picker', $this->settings['dir'] . 'css/field-group.css', array('acf-field-group'), $this->settings['version'] );

		wp_enqueue_script(array(
			'acf-field-group-icon-picker'
		));

		wp_enqueue_style(array(
			'acf-field-group-icon-picker'
		));
	}

	/*
	*  format_value()
	*
	*  This filter is applied to the $value after it is loaded from the db and before it is passed to the create_field action
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value	- the value which was loaded from the database
	*  @param	$post_id - the $post_id from which the value was loaded
	*  @param	$field	- the field array holding all the field options
	*
	*  @return	$value	- the modified value
	*/
	
	function format_value( $value, $post_id, $field ) {
		return $this->_format_value( $value, $post_id, $field );
	}
	
	
	/*
	*  format_value_for_api()
	*
	*  This filter is applied to the $value after it is loaded from the db and before it is passed back to the API functions such as the_field
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value	- the value which was loaded from the database
	*  @param	$post_id - the $post_id from which the value was loaded
	*  @param	$field	- the field array holding all the field options
	*
	*  @return	$value	- the modified value
	*/
	
	function format_value_for_api( $value, $post_id, $field ) {
		return $this->_format_value( $value, $post_id, $field, 'api' );
	}

	private function _format_value( $value, $post_id, $field, $format = null ) {
		$data = array();
		if( is_array( $value ) && count( $value ) > 0 ) {
			$answer_options = $field['answerOptions'];
			if( ! is_array( $answer_options) || count( $answer_options ) <= 0 ) {
				$answer_options = $this->defaults['answerOptions'];
			}
			foreach( $value as $k => $v ) {
				if( $v && ( $v = json_decode( $v, true ) ) ) {
					if( 'api' === $format ) {
						if( in_array( 'url', $answer_options ) ) {
							$v['url'] = icon_picker::url( $v['vid'] );
						}
						
						if( in_array( 'duration', $answer_options ) ) {
							$v['duration'] = icon_picker::duration( $v['vid'], $field['api_key'] );
						}

						if( in_array( 'thumbs', $answer_options ) ) {
							$v['thumbs'] = icon_picker::thumbs( $v['vid'] );
						}

						if( in_array( 'iframe', $answer_options ) ) {
							$v['iframe'] = html_entity_decode( icon_picker::iframe( $v['vid'] ) );
						}
						
						if( ! in_array( 'title', $answer_options ) ) {
							unset( $v['title'] );
						}

						if( ! in_array( 'vid', $answer_options ) ) {
							unset( $v['vid'] );
						}

						if( ! $field['multiple'] ) {
							$data = $v;
						}else{
							$data[$k] = $v;
						}
					}else{
						$data[$k] = $v;
					}
				}
				unset( $value[$k] );
			}
		}
		return $data;
	}
	
}

new acf_field_icon_picker();