<?php 

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
		if ( !empty( $group ) ) {

			$my_query = new WP_Query( array( 
				'post_type' => 'endo_wrc_cpt', 
				'posts_per_page' => $num_posts, 
				'orderby' => 'rand', 
				'tax_query' => array(
					array(
						'taxonomy' => 'endo_wrc_group',
						'field' => 'slug',
						'terms' => $group
					)
				)
			) );
		} else {

			// filter through all entries
			$my_query = new WP_Query( array( 
				'post_type' => 'endo_wrc_cpt', 
				'posts_per_page' => $num_posts, 
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
		$instance['num_posts'] = strip_tags( $new_instance['num_posts'] );
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

		if ( isset( $instance[ 'num_posts' ] ) ) {
			$num_posts = $instance[ 'num_posts'];
		} else {
			$num_posts = 1;
		}

		?>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title: </label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>"
				value="<?php echo $title; ?>" />
		</p>

		<?php
	
			$field_id = $this->get_field_id( 'group' );
			$field_name = $this->get_field_name( 'group' );
			
			$args = array(
				'fields' => 'names'
			);
			$terms = get_terms( 'endo_wrc_group', $args );

			$count = count($terms);

			if ( $count > 0 ){

				echo '<p>';
				echo '<label for="' . $this->get_field_id( 'group' ) . '">Group: </label>';
				echo '<select class="widefat" name="' . $field_name . '" id="' . $field_id . '">';
				echo "<option value='' " . selected ( '', '' ) . "> - </option>";
				foreach ( $terms as $term ) {
					echo "<option value='$term' " . selected( $group, $term ) . ">" . $term . "</option>";
				}
				echo "</select>";
				echo '</p>';
			} 
			else {
				echo '<p>Create a group to organize multiple widgets.</p>';
			}

			echo '<p>';
			echo '<label for="' . $this->get_field_id( 'num_posts' ) . '">Number of Posts to Show at Once: </label>';

			echo '<input type="text" id=" ' . $this->get_field_id( 'num_posts' ) . '" name="' . $this->get_field_name( 'num_posts' ) . '"
				value="' . $num_posts . '" />';
			echo '</p>';


	}

}  // end class