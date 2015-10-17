<?php
/**
* Alert Widget
*/
class Fh_Content_Alert_Widget extends WP_Widget {
	/**
	* Register widget with WordPress
	*/
	function __construct() {
		parent::__construct(
			'fh_content_alert_widget',
			__('500 Content Alert Widget', 'fivehundred'),
			array('description' => __('Background image with headline overlay', 'fivehundred')),
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
			if (isset($instance['custom_class'])) {
				$custom_class = html_entity_decode($instance['custom_class']);
			}
			else {
				$custom_class = '';
			}
			$image = html_entity_decode($instance['image']);
			if (isset($instance['text_color'])) {
				$text_color = html_entity_decode($instance['text_color']);
			}
			else {
				$text_color = '';
			}
			echo '<div class="fh_widget ign-content-alert '.$custom_class.'" style="background-image: url('.$image.');">';
			echo '<h3 style="color:'.$text_color.' !important;">'.$title.'</h3>';
			echo '</div>';
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
		if (isset($instance['image'])) {
			$image = $instance['image'];
		}
		if (isset($instance['title'])) {
			$title = $instance['title'];
		}
		if (isset($instance['custom_class'])) {
			$custom_class = $instance['custom_class'];
		}
		if (isset($instance['text_color'])) {
			$text_color = $instance['text_color'];
		}
		$form = '<p>';
		$form .= '<label for="'.$this->get_field_id( 'title' ).'">'.__('Title', 'fivehundred').':';
		$form .= '<input class="widefat" type="text" id="'.$this->get_field_id( 'title' ).'" name="'.$this->get_field_name( 'title' ).'" value="'.(isset($title) ? $title : '').'"/>';
		$form .= '</label></p>';
		$form .= '<p>';
		$form .= '<label for="'.$this->get_field_id( 'image' ).'">'.__('Image', 'fivehundred').':</label>';
		$form .= '<input type="text" class="widefat alert-image" id="'.$this->get_field_id( 'image' ).'" name="'.$this->get_field_name( 'image' ).'" value="'.(!empty($image) ? $image : '').'">';
		$form .='<label for="'.$this->get_field_id( 'text_color' ).'">'.__('Text Color (Hex Color including # - ie: #ffffff) ', 'fivehundred').':';
		$form .= '<input class="widefat" type="text" id="'.$this->get_field_id( 'text_color' ).'" name="'.$this->get_field_name( 'text_color' ).'" value="'.(isset($text_color) ? $text_color : '').'"/>';
		$form .= '</p>';
		$form .= '<button class="button fh_media_button" name="fh_media_button" id="'.$this->get_field_id( 'media' ).'">'.__('Add Image', 'fivehundred').'</button>';
		$form .='<label for="'.$this->get_field_id( 'custom_class' ).'">'.__('Custom Class Name (not required)', 'fivehundred').':';
		$form .= '<input class="widefat" type="text" id="'.$this->get_field_id( 'custom_class' ).'" name="'.$this->get_field_name( 'custom_class' ).'" value="'.(isset($custom_class) ? $custom_class : '').'"/>';
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
		$instance['image'] = esc_attr($new_instance['image']);
		$instance['custom_class'] = esc_attr($new_instance['custom_class']);
		$instance['text_color'] = esc_attr($new_instance['text_color']);
		return $instance;
	}
}
?>