<?php

class Sweet_Widget_Post_Type_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		parent::__construct(
			'sw-widget',
			'Sweet Widget',
			array(
				'classname' => 'sw-widget',
				'description' => 'Sweet widget!',
			)
		);
	}

	/**
	 * Show the sw_widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		$post = get_post( $instance['post_id'] );

		if ( $post->post_status != 'publish' ) {
			return;
		}

		$widget = $args['before_widget'];

		if ( !empty( $instance['hide_title'] ) ) {
			$widget.= $args['before_title'] .
			            apply_filters( 'the_title', $post->post_title ) .
			          $args['after_title'];
		}

		$widget.= apply_filters( 'the_content', $post->post_content );
		$widget.= $args['after_widget'];

		echo $widget;
	}

	/**
	 * Widget settings form
	 *
	 * @param array $instance The widget options
	 *
	 * @return string|void
	 */
	public function form( $instance ) {
		$values = wp_parse_args( $instance, array(
			'hide_title' => null,
			'post_id' => null,
		) );

		// create a useful title to display in the widget header
		$title = '-none-';
		if ( $values['post_id'] ) {
			$title = get_the_title( $values['post_id'] );

			$status = get_post_status( $values['post_id'] );
			if ( $status != 'publish' ) {
				$title.= ' - ' . $status;
			}
		}
		?>
		<div class="sweet-widget-post-type">
			<input type="hidden" id="<?php echo $this->get_field_id( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>">

			<p>
				<label for="<?php echo $this->get_field_id( 'post_id' ); ?>">
					<strong><?php _e( 'Sweet Widget' ); ?></strong>
				</label>
				<select
					id="<?php echo $this->get_field_id( 'post_id' ); ?>"
					class="widefat"
					name="<?php echo $this->get_field_name( 'post_id' ); ?>">
					<?php foreach( $this->get_sweet_widgets( array( 'publish', 'draft' ) ) as $index => $post ) : ?>
						<option
							value="<?php echo esc_attr( $post->ID ); ?>"
							<?php selected( $post->ID, $values['post_id'] ); ?>>
							<?php echo esc_attr( $post->post_title ); ?>
							<?php if ( $post->post_status != 'publish' ) : ?>
							- <?php echo esc_attr( $post->post_status ); ?>
							<?php endif; ?>
						</option>
					<?php endforeach; ?>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'hide_title' ); ?>">
					<input
						type="checkbox"
						id="<?php echo $this->get_field_id( 'hide_title' ); ?>"
						name="<?php echo $this->get_field_name( 'hide_title' ); ?>"
						<?php checked( !empty( $values['hide_title'] ) ); ?>>
					<strong><?php _e( 'Hide Title' ); ?></strong>
				</label>
			</p>
			<p class="help">
				<?php _e( 'Select the Sweet Widget you would like to display, and choose whether or not to hide its title' ); ?>
			</p>
		</div>
		<?php
	}

	/**
	 * Save the widget form
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 *
	 * @return array|void
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array(
			'hide_title' => isset( $new_instance['hide_title'] ),
			'post_id' => isset( $new_instance['post_id'] ) ? absint( $new_instance['post_id'] ) : null,
		);

		return $instance;
	}

	/**
	 * Get an array of sweet widgets as WP_Post objects
	 *
	 * @param array $post_status
	 *
	 * @return array
	 */
	function get_sweet_widgets( $post_status = array( 'publish' ) ){
		return get_posts( array(
			'post_type' => 'sw_widget',
			'post_status' => $post_status,
			'posts_per_page' => -1,
			'orderby' => 'title',
			'order' => 'ASC',
			'ignore_sticky_posts' => true,
		) );
	}
}
