<?php
/**
 * Pinit: Pinterest Widget Class
 *
 * @since 0.1
 */
class pit_pinterest extends WP_Widget {
	// Constructor
	function __construct() {
		parent::__construct(
			'pit_pinterest', // Base ID
			__( 'Pinterest (Pinit)', 'pinit' ), // Name
			array( 'description' => __( 'Show Pit, Profile or Board Widget.', 'pinit' ) ) // Args
		);
	}

	/**
	 * Front-end display of widget
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args. Widget arguments.
	 * @param array $instance. Saved values from database.
	 */
	public function widget( $args, $instance ) {
		extract( $args );

		// Widget options
		$title = apply_filters( 'widget_title', $instance['title'] );
		$purl = $instance['purl'];
		$imgWidth = $instance['imgWidth'];
		$boxHeight = $instance['boxHeight'];
		$boxWidth = $instance['boxWidth'];
		$select = $instance['select'];

		echo $before_widget;
		if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;

		// Inizialize the output
		$output = '<a ';

		// Select which type of widget to display
		switch ($select) {
			case 'profile':
				$ptype = 'embedUser';
				break;
			case 'board':
				$ptype = 'embedBoard';
				break;
			default:
				$ptype = 'embedPin';
				break;
		}

		$output .= 'data-pin-do="' . $ptype . '" ';

		// URL
		if ( ! empty( $purl ) )
			$output .= 'href="' . $purl . '" ';

		// Image Width
		if ( ! empty( $imgWidth ) )
			$output .= 'data-pin-scale-width="' . $imgWidth . '" ';

		// Board Height
		if ( ! empty( $boxHeight ) )
			$output .= 'data-pin-scale-height="' . $boxHeight . '" ';

		// Board Width
		if ( ! empty( $boxWidth ) )
			$output .= 'data-pin-board-width="' . $boxWidth . '"';

		$output .= '></a>';

		echo $output;
		echo $after_widget;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance. Previously saved values from database.
	 */
	public function form( $instance ) {

		// Widget title
		$title = isset( $instance['title'] ) ? esc_attr($instance['title']) : '';
		// Target URL
		$purl = isset( $instance['purl'] ) ? esc_attr($instance['purl']) : '';
		// Image Width
		$imgWidth = isset( $instance['imgWidth'] ) ? esc_attr($instance['imgWidth']) : '';
		// Board Height
		$boxHeight = isset( $instance['boxHeight'] ) ? esc_attr($instance['boxHeight']) : '';
		// Board Width
		$boxWidth = isset( $instance['boxWidth'] ) ? esc_attr($instance['boxWidth']) : '';
		// Widget Type Selector
		$select = isset( $instance['select'] ) ? esc_attr($instance['select']) : 'pin';
		?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', 'pinit' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>">
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('select'); ?>"><?php _e( 'Type:', 'pinit' ); ?></label>
		</p>
		<ul>
			<?php $options = array( 'pin', 'profile', 'board' );
				foreach($options as $option) : ?>

			<li>
				<label>
					<input id="<?php echo $this->get_field_id('select'); ?>-<?php echo $option; ?>" name="<?php echo $this->get_field_name('select'); ?>" type="radio" value="<?php echo $option; ?>" <?php checked( $select, $option, true ); ?>>
					<?php _e( ucfirst($option) ); ?>
				</label>
			</li>

			<?php endforeach; ?>
		</ul>

		<p class="pin-control profile-control board-control">
			<label for="<?php echo $this->get_field_id('purl'); ?>">
			<?php foreach ( $options as $option ) : ?>
				<span class="<?php echo $option; ?>-help"><?php printf( __( 'Pinterest %1$s URL:', 'pinit' ), ucfirst( $option ) ); ?></span>
			<?php endforeach; ?>
			</label>
			<input class="widefat" id="<?php echo $this->get_field_id('purl'); ?>" name="<?php echo $this->get_field_name('purl'); ?>" type="text" value="<?php echo $purl; ?>">
			<br>
			<small><?php _e( 'E.g.', 'pinit' ); ?>
				<span class="pin-help">http://www.pinterest.com/pin/<em>pin_id</em>/</span>
				<span class="profile-help">http://www.pinterest.com/<em>username</em>/</span>
				<span class="board-help">http://www.pinterest.com/<em>username</em>/<em>boardname</em>/</span>
			</small>
		</p>

		<p class="profile-control board-control">
			<label for="<?php echo $this->get_field_id('imgWidth'); ?>"><?php _e( 'Image Width:', 'pinit' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('imgWidth'); ?>" name="<?php echo $this->get_field_name('imgWidth'); ?>" type="text" value="<?php echo $imgWidth; ?>" min="60">
			<br>
			<small><?php _e( 'min: 60; leave blank for 80', 'pinit' ); ?></small>
		</p>

		<p class="profile-control board-control">
			<label for="<?php echo $this->get_field_id('boxHeight'); ?>"><?php _e( 'Board Height:', 'pinit' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('boxHeight'); ?>" name="<?php echo $this->get_field_name('boxHeight'); ?>" type="text" value="<?php echo $boxHeight; ?>" min="60">
			<br>
			<small><?php _e( 'min: 60; leave blank for 400', 'pinit' ); ?></small>
		</p>

		<p class="profile-control board-control">
			<label for="<?php echo $this->get_field_id('boxWidth'); ?>"><?php _e( 'Board Width:', 'pinit' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('boxWidth'); ?>" name="<?php echo $this->get_field_name('boxWidth'); ?>" type="text" value="<?php echo $boxWidth; ?>" min="130">
			<br>
			<small><?php _e( 'min: 130; leave blank for 400', 'pinit' ); ?></small>
		</p>

		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance. Values just sent to be saved.
	 * @param array $old_instance. Previously saved values from database.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		$instance['purl'] = $new_instance['purl'];
		$instance['imgWidth'] = $new_instance['imgWidth'];
		$instance['boxHeight'] = $new_instance['boxHeight'];
		$instance['boxWidth'] = $new_instance['boxWidth'];
		$instance['select'] = $new_instance['select'];

		return $instance;
	}
}