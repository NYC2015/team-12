<?php
/**
* Projects Grid Widget using a Shortcode
*/
class Fh_Project_Grid_Widget extends WP_Widget {
	/**
	* Register widget with WordPress
	*/
	function __construct() {
		parent::__construct(
			'fh_project_grid_widget',
			__('500 Project Grid', 'fivehundred'),
			array('description' => __('A widget to display projects in a grid', 'fivehundred')),
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

			// Calling the shortcode for generating projects grid

			// Getting the projects depending on the arguments
			$args = array('post_type' => 'ignition_product',
				'posts_per_page' => (isset($instance['max']) ? $instance['max'] : get_option('posts_per_page')),
				'paged' => 1,
			);
			
			// Order by
			if (isset($instance['orderby'])){
				if ($instance['orderby'] == 'days_left') {
					$args['orderby'] = 'meta_value_num';
					$args['meta_key'] = 'ign_days_left';
				} else if ($instance['orderby'] == 'percent_raised') {
					$args['orderby'] = 'meta_value_num';
					$args['meta_key'] = 'ign_percent_raised';
				} else if ($instance['orderby'] == 'funds_raised') {
					$args['orderby'] = 'meta_value_num';
					$args['meta_key'] = 'ign_fund_raised';
				} else {
					// reserved for later use
					$args['orderby'] = $instance['orderby'];
				}
				// Order
				if (!empty($instance['order'])) {
					$args['order'] = $instance['order'];
				}
				else {
					$args['order'] = 'DESC';
				}
			}
			// Category
			$tax_slug = $instance['category'];
			if (!empty($tax_slug)) {
				$tax_cat = get_term_by('slug', $tax_slug, 'project_category');
				if (!empty($tax_cat)) {
					$args['tax_query'] = array(
						array(
							'taxonomy' => 'project_category',
							'field' => 'slug',
							'terms' => $tax_slug
						)
					);
				}
			}
			// Author
			if (!empty($instance['author'])) {
				$args['author_name'] = $instance['author'];
			}
			

			$query = new WP_Query($args);
			if ( $query->have_posts() ){
				echo '<div class="fh_widget">';
				echo (!empty($instance['title']) ? '<h2 class="entry-title">'.$instance['title'].'</h2>' : '');
				echo '<div class="project-grid fh_widget fullwindow '.(isset($instance['classes']) ? $instance['classes'] : '').'">';
				while ( $query->have_posts() ) {
					$query->the_post();
					get_template_part('project', 'summary');
				}
				echo '</div>';
				echo '</div>';
				echo '<div style="clear: both;"></div>';
			}

			// Template
			// include_once plugin_dir_path( __FILE__ ).'../templates/_projectGrid.php';
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

		if (isset($instance['max'])) {
			$max = $instance['max'];
		}
		if (isset($instance['category'])) {
			$category = $instance['category'];
		}
		// if (isset($instance['deck'])) {
		// 	$deck = $instance['deck'];
		// }
		if (isset($instance['orderby'])) {
			$orderby = $instance['orderby'];
		}
		if (isset($instance['order'])) {
			$order = $instance['order'];
		} else {
			$order = 'ASC';
		}
		if (isset($instance['author'])) {
			$author = $instance['author'];
		} else {
			$author = '';
		}
		
		// Title field
		$form = '<p>';
		$form .= '<label for="'.$this->get_field_id( 'title' ).'">'.__('Title', 'fivehundred').':';
		$form .= '<input type="text" class="widefat" id="'.$this->get_field_id( 'title' ).'" name="'.$this->get_field_name( 'title' ).'" value="'.(isset($instance['title']) ? $instance['title'] : '').'">';
		$form .= '</label></p>';
		$form .= '<div style="font-size: 90%; color: #666; margin-bottom: 10px; margin-top: -10px;">'.__('Enter title to display above widget', 'fivehundred').'</div>';
	
		// Maximum projects field
		$form .= '<p>';
		$form .= '<label for="'.$this->get_field_id( 'max' ).'">'.__('Maximum Projects', 'fivehundred').':';
		$form .= '<input type="text" class="widefat" id="'.$this->get_field_id( 'max' ).'" name="'.$this->get_field_name( 'max' ).'" value="'.(isset($max) ? $max : '').'">';
		$form .= '</label></p>';
		$form .= '<div style="font-size: 90%; color: #666; margin-bottom: 10px; margin-top: -10px;">'.__('The number of projects you want to show in the grid. Using -1 will show all filtered projects', 'fivehundred').'</div>';
		// Category field
		$form .= '<p>';
		$form .= '<label for="'.$this->get_field_id( 'category' ).'">'.__('Project Category (Optional)', 'fivehundred').':';
		$form .= '<input type="text" class="widefat" id="'.$this->get_field_id( 'category' ).'" name="'.$this->get_field_name( 'category' ).'" value="'.(isset($category) ? $category : '').'">';
		$form .= '<div style="font-size: 90%; color: #666; margin-bottom: 10px; margin-top: -10px;">'.__('Enter the slug of the Project Category to only show projects from certain categories', 'fivehundred').'</div>';
		$form .= '</label></p>';
		// OrderBy field
		$form .= '<p>';
		$form .= '<label for="'.$this->get_field_id( 'orderby' ).'">'.__('Order By (Optional)', 'fivehundred').':<br/>';
		$form .= '<select id="'.$this->get_field_id( 'orderby' ).'" name="'.$this->get_field_name( 'orderby' ).'">
					<option value="days_left" '.(isset($orderby) && $orderby == 'days_left' ? 'selected="selected"' : '').'>'.__('Days Left', 'fivehundred').'</option>
					<option value="percent_raised" '.(isset($orderby) && $orderby == 'percent_raised' ? 'selected="selected"' : '').'>'.__('Percent Raised', 'fivehundred').'</option>
					<option value="funds_raised" '.(isset($orderby) && $orderby == 'funds_raised' ? 'selected="selected"' : '').'>'.__('Funds Raised', 'fivehundred').'</option>
					<option value="title" '.(isset($orderby) && $orderby == 'title' ? 'selected="selected"' : '').'>'.__('Title', 'fivehundred').'</option>
					<option value="date" '.((isset($orderby)) ? (($orderby == 'date') ? 'selected="selected"' : '') : 'selected="selected"').'>'.__('Date', 'fivehundred').'</option>
				  </select>';
		$form .= '</label></p>';
		$form .= '<div style="font-size: 90%; color: #666; margin-bottom: 10px; margin-top: -10px;">'.__('You can set what you want the projects to order by. Possible values are days_left, percent_raised, funds_raised, title, date (default)', 'fivehundred').'</div>';
		// Order field
		$form .= '<p>';
		$form .= '<label for="'.$this->get_field_id( 'order' ).'">'.__('Order (Optional)', 'fivehundred').':<br/>';
		$form .= '<select id="'.$this->get_field_id( 'order' ).'" name="'.$this->get_field_name( 'order' ).'">
					<option value="ASC" '.(isset($order) && $order == 'ASC' ? 'selected="selected"' : '').'>Ascending</option>
					<option value="DSC" '.(isset($order) && $order == 'DSC' ? 'selected="selected"' : '').'>Descending</option>
				  </select>';
		$form .= '</label></p>';
		$form .= '<div style="font-size: 90%; color: #666; margin-bottom: 10px; margin-top: -10px;">'.__('You can set the orderby to order ascending or descending', 'fivehundred').'</div>';
		// Author field
		$form .= '<p>';
		$form .= '<label for="'.$this->get_field_id( 'author' ).'">'.__('Author (Optional)', 'fivehundred').':';
		$form .= '<input type="text" class="widefat" id="'.$this->get_field_id( 'author' ).'" name="'.$this->get_field_name( 'author' ).'" value="'.(isset($author) ? $author : '').'">';
		$form .= '</label></p>';
		$form .= '<div style="font-size: 90%; color: #666; margin-bottom: 10px; margin-top: -10px;">'.__('Enter the username of the author for the post you wish to display. Only one author is allowed', 'fivehundred').'</div>';
		// Classes field
		$form .= '<p>';
		$form .= '<label for="'.$this->get_field_id( 'classes' ).'">'.__('Custom classes', 'fivehundred').':';
		$form .= '<input type="text" class="widefat" id="'.$this->get_field_id( 'classes' ).'" name="'.$this->get_field_name( 'classes' ).'" value="'.(isset($instance['classes']) ? $instance['classes'] : '').'">';
		$form .= '</label></p>';
		$form .= '<div style="font-size: 90%; color: #666; margin-bottom: 10px; margin-top: -10px;">'.__('List of custom classes to be applied to grid wrapper, separated via space', 'fivehundred').'</div>';
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
		$instance['classes'] = sanitize_text_field($new_instance['classes']);
		$instance['title'] = sanitize_text_field($new_instance['title']);
		$instance['max'] = absint($new_instance['max']);
		$instance['category'] = sanitize_text_field($new_instance['category']);
		// $instance['deck'] = esc_attr($new_instance['deck']);
		$instance['orderby'] = esc_attr($new_instance['orderby']);
		$instance['order'] = esc_attr($new_instance['order']);
		$instance['author'] = sanitize_text_field($new_instance['author']);

		return $instance;
	}
}
?>