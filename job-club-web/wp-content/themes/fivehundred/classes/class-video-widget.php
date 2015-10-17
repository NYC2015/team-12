<?php
/**
* Video Widget
*/
class Fh_Video_Widget extends WP_Widget {
	/**
	* Register widget with WordPress
	*/
	function __construct() {
		parent::__construct(
			'fh_video_widget',
			__('500 Video Widget', 'fivehundred'),
			array('description' => __('Use to embed a video on your home page', 'fivehundred')),
			array('width' => 'auto'));
	}
	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	function widget($args, $instance) {
		if (!empty($instance)) {
			$title = $instance['title'];
			$embed = html_entity_decode($instance['embed']);
			echo '<div class="fh_widget"><div class="ign-video-headline ign-text-container"><h3>'.$title.'</h3></div>';
			echo '<div class="ign-content-video">'.$embed.'</div></div>';
		}
	}
	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	function form($instance) {
		if (isset($instance['embed'])) {
			$embed = $instance['embed'];
		}
		if (isset($instance['title'])) {
			$title = $instance['title'];
		}
		$form = '<p>';
		$form .= '<label for="'.$this->get_field_id( 'title' ).'">'.__('Title', 'fivehundred').':';
		$form .= '<input class="widefat" type="text" id="'.$this->get_field_id( 'title' ).'" name="'.$this->get_field_name( 'title' ).'" value="'.(isset($title) ? $title : '').'"/>';
		$form .= '</label></p>';
		$form .= '<textarea class="widefat" rows="16" cols="20" id="'.$this->get_field_id( 'embed' ).'" name="'.$this->get_field_name( 'embed' ).'">';
		$form .= (!empty($embed) ? $embed : '');
		$form .= '</textarea>';
		echo $form;
	}
	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = esc_attr(strip_tags($new_instance['title']));
		$instance['embed'] = esc_attr($new_instance['embed']);
		return $instance;
	}
}
?>