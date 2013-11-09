<?php
/*
Plugin Name: Random Content 
Plugin URI: http://www.endocreative.com
Description: Randomly display content from a custom post type using a widget
Author: Endo Creative
Author URI: http://www.endocreative.com
Version: 0.2
*/

class Endo_Wrc_Post_Type {

	public function __construct() {

		$this->register_post_type();
		$this->register_taxonomy();
		add_shortcode( 'random', array( &$this, 'shortcode') );
	}
	
	public function register_post_type() {

		$args = array(
			'labels' => array(
				'name' => 'Random Content',
				'singular_name' => 'Random Content',
				'add_new' => 'Add New Random Content',
				'add_new_item' => 'Add New Random Content',
				'edit_item' => 'Edit Random Content',
				'new_item' => 'Add New Random Content',
				'view_item' => 'View Random Content',
				'search_items' => 'Search Random Contents',
				'not_found' => 'No Random Content Found',
				'not_found_in_trash' => 'No Random Content Found in Trash'
			),
			'show_ui' => true,
			'supports' => array(
				'title',
				'editor'
			)
		);
		register_post_type( 'endo_wrc_cpt', $args );

	}

	public function register_taxonomy() {

		register_taxonomy(
			'endo_wrc_group',
			'endo_wrc_cpt',
			array(
				'label' => __( 'Group' ),
				'hierarchical' => true
			)
		);
	}

	public function shortcode( $atts ) {
		extract( shortcode_atts( array(
			'group_id' => ''
			), $atts ));

		// if $group_id is set, then filter results by $group_id
		if ( $group_id ) {
			$my_query = new WP_Query( array( 
				'post_type' => 'endo_wrc_cpt', 
				'posts_per_page' => 1, 
				'orderby' => 'rand', 
				'tax_query' => array(
					array(
						'taxonomy' => 'endo_wrc_group',
						'field' => 'id',
						'terms' => $group_id
					)
				)
			) );

		}
		else {
			// filter through all entries
			$my_query = new WP_Query( array( 
				'post_type' => 'endo_wrc_cpt', 
				'posts_per_page' => 1, 
				'orderby' => 'rand'
			) );
		}

		if ( $my_query->have_posts() ) {

			while ( $my_query->have_posts() ) : $my_query->the_post();
				
				$content = apply_filters('the_content', get_the_content() );
					
			endwhile;

		} else {
			$content = 'No posts found.';
		}

		wp_reset_postdata();

		return $content;
		
	}


} // end class
		
add_action('init', 'endo_wrc_cpt_class');

	function endo_wrc_cpt_class() {
		new Endo_Wrc_Post_Type;
	}


/*  ==========  Widgetizing the display of Random Content ============= */ 


class Endo_WRC_Widget extends WP_Widget {
	
	function __construct()
	{
		$options = array(
			'description'	=> 'Display random content from the selected group',
			'name' 			=> 'Random Content'
		);
		
		parent::__construct('endo_wrc_widget', 'WRC_Widget', $options);
	}

	public function widget( $args, $instance ) {

		extract($args);
		extract($instance);
		
		$title = apply_filters('widget_title', $title);
		
		echo $before_widget;

		if ( !empty( $title) )
			echo $before_title . $title . $after_title;

		// if $group is set, then filter results by $group
		if ( $group ) {
			$my_query = new WP_Query( array( 
				'post_type' => 'endo_wrc_cpt', 
				'posts_per_page' => 1, 
				'orderby' => 'rand', 
				'tax_query' => array(
					array(
						'taxonomy' => 'endo_wrc_group',
						'field' => 'slug',
						'terms' => $group
					)
				)
			) );
		}
		else {
			// filter through all entries
			$my_query = new WP_Query( array( 
				'post_type' => 'endo_wrc_cpt', 
				'posts_per_page' => 1, 
				'orderby' => 'rand'
			) );
		}

		while ( $my_query->have_posts() ) : $my_query->the_post();
			
			the_content();
				
		endwhile;

		wp_reset_postdata();
						
		echo $after_widget;

	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['group'] = strip_tags( $new_instance['group'] );
		return $instance;
	}
	
	public function form( $instance ) {

		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		} 
		else {
			$title = 'New title';
		}
		if ( isset( $instance[ 'group' ] ) ) {
			$group = $instance[ 'group'];
		}

		?>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title: </label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
				name="<?php echo $this->get_field_name( 'title' ); ?>"
				value="<?php echo $title; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'group' ); ?>">Group: </label>
			
		<?php
	
			$field_id = $this->get_field_id( 'group' );
			$field_name = $this->get_field_name( 'group' );
			
			$args = array(
				'fields' => 'names'
			);
			$terms = get_terms( 'endo_wrc_group', $args );


			$count = count($terms);
			if ( $count > 0 ){
				echo '<select class="widefat" name="' . $field_name . '" id="' . $field_id . '">';
				foreach ( $terms as $term ) {
					echo "<option value='$term' " . selected( $group, $term ) . ">" . $term . "</option>";
				}
				echo "</select>";
			} 
			else {
				echo "<p>Create a group to organize multiple widgets.</p>";
			}

		?>
			
		
		</p>
		
		
		
		<?php
	}

}  // end class

add_action('widgets_init', 'register_endo_wrc_widget');

function register_endo_wrc_widget() {
	register_widget('Endo_WRC_Widget');
	
} 