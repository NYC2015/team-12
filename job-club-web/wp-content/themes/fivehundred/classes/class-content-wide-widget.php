<?php
/**
* Content Full Alt Widget
*/
class Fh_Content_Fullalt_Widget extends WP_Widget {
	/**
	* Register widget with WordPress
	*/
	function __construct() {
		parent::__construct(
			'fh_content_fullalt_widget',
			__('500 Content Wide', 'fivehundred'),
			array('description' => __('A full-width content area for your home page, with an automatic style based on your Color Customization preferences.', 'fivehundred')),
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
			if (isset($instance['title'])) {
				$title = $instance['title'];
			}
			else {
				$title = '';
			}
			if (isset($instance['height'])) {
				$height = html_entity_decode($instance['height']);
			}
			else {
				$height = 100;
			}
			if (isset($instance['text'])) {
				$text = html_entity_decode($instance['text']);
			}
			else {
				$text = '';
			}
			if (isset($instance['custom_class'])) {
				$custom_class = html_entity_decode($instance['custom_class']);
			}
			else {
				$custom_class = '';
			}
			$id = $args['widget_id'];
			$idpos = stripos($id, '-');
			$num_id = substr($id, $idpos + 1);
			if ($num_id & '1') {
				$oddeven = 'even';
			}
			else {
				$oddeven = 'odd';
			}
			echo '<div class="fh_widget fullwindow contentwide '.$custom_class.'" '.($height > 0 ? 'style="height: '.$height.'px;"' : '').' id="'.$id.'"><div class="fullwindow-internal cf '.$oddeven.'"><div class="constrained"><div class="ign-content-fullalt" style="height: '.$height.'px;"><div class="ign-text-container"><h3>'.$title.'</h3>';
			echo '<div class="ign-content-text">'.$text.'</div></div></div></div></div></div>';
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
		if (isset($instance['text'])) {
			$text = $instance['text'];
		}
		if (isset($instance['height'])) {
			$height = $instance['height'];
		}
		if (isset($instance['title'])) {
			$title = $instance['title'];
		}
		if (isset($instance['custom_class'])) {
			$custom_class = $instance['custom_class'];
		}
		$form = '<p>';
		$form .= '<label for="'.$this->get_field_id( 'title' ).'">'.__('Title', 'fivehundred').':';
		$form .= '<input class="widefat" type="text" id="'.$this->get_field_id( 'title' ).'" name="'.$this->get_field_name( 'title' ).'" value="'.(isset($title) ? $title : '').'"/>';
		$form .= '</label></p>';
		$form .= '<p>';
		$form .= '<label for="'.$this->get_field_id( 'height' ).'">'.__('Content Height (numbers only, measured in pixels)', 'fivehundred').':';
		$form .= '<input type="text" class="widefat" id="'.$this->get_field_id( 'height' ).'" name="'.$this->get_field_name( 'height' ).'" value="'.(isset($height) ? $height : '').'">';
		$form .= '</label><span style="font-size: 90%; color: #666;">'.__('This must be set to ensure content below it does not roll up underneath it.', 'fivehundred').'</span></p>';
		$form .= '<textarea class="widefat" rows="16" cols="20" id="'.$this->get_field_id( 'text' ).'" name="'.$this->get_field_name( 'text' ).'">';
		$form .= (!empty($text) ? $text : '');
		$form .= '</textarea>';
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
		$instance['text'] = esc_attr($new_instance['text']);
		$instance['height'] = esc_attr(strip_tags($new_instance['height']));
		$instance['custom_class'] = esc_attr($new_instance['custom_class']);

		return $instance;
	}
}
?>