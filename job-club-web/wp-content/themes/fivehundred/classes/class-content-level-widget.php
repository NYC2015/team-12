<?php
/**
* Content Level Widget
*/
class Fh_Content_Level_Widget extends WP_Widget {
	/**
	* Register widget with WordPress
	*/
	function __construct() {
		parent::__construct(
			'fh_content_level_widget',
			__('500 Content Level', 'fivehundred'),
			array('description' => __('A widget to display full descriptions of your Reward Levels.', 'fivehundred')),
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
			if (isset($instance['amount'])) {
				$amount = html_entity_decode($instance['amount']);
			}
			else {
				$amount = 100;
			}
			$text = html_entity_decode($instance['text']);
			echo '<div class="fh_widget ign-content-level"><div class="ign-text-container"><h3>'.$title.'<div class="amount">'.$amount.'</div></h3>';
			echo '<div class="ign-content-text">'.$text.'</div></div></div>';
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
		if (isset($instance['amount'])) {
			$amount = $instance['amount'];
		}
		if (isset($instance['title'])) {
			$title = $instance['title'];
		}
		$form = '<p>';
		$form .= '<label for="'.$this->get_field_id( 'title' ).'">'.__('Title', 'fivehundred').':';
		$form .= '<input class="widefat" type="text" id="'.$this->get_field_id( 'title' ).'" name="'.$this->get_field_name( 'title' ).'" value="'.(isset($title) ? $title : '').'"/>';
		$form .= '</label></p>';
		$form .= '<p>';
		$form .= '<label for="'.$this->get_field_id( 'amount' ).'">'.__('Level Price', 'fivehundred').':';
		$form .= '<input type="text" class="widefat" id="'.$this->get_field_id( 'amount' ).'" name="'.$this->get_field_name( 'amount' ).'" value="'.(isset($amount) ? $amount : '').'">';
		$form .= '</label></p>';
		$form .= '<textarea class="widefat" rows="16" cols="20" id="'.$this->get_field_id( 'text' ).'" name="'.$this->get_field_name( 'text' ).'">';
		$form .= (!empty($text) ? $text : '');
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
		$instance['text'] = esc_attr($new_instance['text']);
		$instance['amount'] = esc_attr($new_instance['amount']);
		return $instance;
	}
}
?>