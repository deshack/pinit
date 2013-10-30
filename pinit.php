<?php
/**
 * Plugin Name: Pinit
 * Plugin URI: https://github.com/deshack/pinit
 * Description: Handy plugin that adds Pinterest Follow Button, Pin Widget, Profile Widget and Board Widget to your WordPress site.
 * Author: Mattia Migliorini
 * Version: 0.3
 * Author URI: http://www.deshack.net
 * License: GPLv2 or later
 */

// Load text domain
function pit_text_start() {
	load_plugin_textdomain( 'pit', false, '/lanuages' );
}
add_action( 'init', 'pit_text_start' );

// Load the Pinterest JavaScript
function pit_scripts() {
	wp_enqueue_script( 'pinit', plugins_url( '/js/pinit.js', __FILE__ ), array(), '20131028', true );
}
add_action( 'wp_enqueue_scripts', 'pit_scripts' );

/**
 * Pinterest Profile Widget Class
 *
 * @since 0.1
 */
class pit_widget_profile extends WP_Widget {
	// Constructor
	function __construct() {
		parent::__construct(
			'pit_widget_profile', // Base ID
			__( 'Pinterest Profile Widget', 'pit' ), // Name
			array( 'description' => __( 'Show up to 30 of your latest pins on your site.', 'pit' ) ) // Args
		);
	}

	/**
	 * Front-end display of widget
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args. Widget arguments.
	 * @param rray $instance. Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		$user = $instance['user'];
		$image_width = $instance['image_width'];
		$width = $instance['width'];
		$height = $instance['height'];

		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];

		$data_image_width = '';
		$data_width = '';
		$data_height = '';

		if ( ! empty( $image_width ) )
			$data_image_width = ' data-pin-scale-width="' . $image_width . '"';
		if ( ! empty( $height ) )
			$data_height = ' data-pin-scale-height="' . $height . '"';
		if ( ! empty( $width ) )
			$data_width = ' data-pin-board-width="' . $width . '"';

		if ( ! empty( $user ) )
			echo '<a data-pin-do="embedUser" href="http://www.pinterest.com/' . $user . '/"' . $data_image_width . $data_height . $data_width . '></a>';

		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance. Previously saved values from database.
	 */
	public function form( $instance ) {
		?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', 'pit' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>">
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('user'); ?>"><?php _e( 'Pinterest Username:', 'pit' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('user'); ?>" name="<?php echo $this->get_field_name('user'); ?>" type="text" value="<?php echo esc_attr( $instance['user'] ); ?>">
			<br>
			<small>http://www.pinterest.com/<strong>username</strong>/</small>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('image_width'); ?>"><?php _e( 'Image Width:', 'pit' ); ?></label>
			<input class="widefat pit-image-width" id="<?php echo $this->get_field_id('image_width'); ?>" name="<?php echo $this->get_field_name('image_width'); ?>" type="text" value="<?php echo esc_attr( $instance['image_width'] ); ?>">
			<br>
			<small><?php _e( 'min: 60; leave 0 or blank for 92', 'pit' ); ?></small>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('height'); ?>"><?php _e( 'Board Height:', 'pit' ); ?></label>
			<input class="widefat pit-height" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo esc_attr( $instance['height'] ); ?>">
			<br>
			<small><?php _e( 'min: 60; leave 0 or blank for 175', 'pit' ); ?></small>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('width'); ?>"><?php _e( 'Board Width:', 'pit' ); ?></label>
			<input class="widefat pit-width" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="text" value="<?php echo esc_attr( $instance['width'] ); ?>">
			<br>
			<small><?php _e( 'min: 130; leave 0 or blank for auto', 'pit' ); ?></small>
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
		$instance['user'] = $new_instance['user'];
		$instance['image_width'] = intval($new_instance['image_width']);
		$instance['height'] = intval($new_instance['height']);
		$instance['width'] = intval($new_instance['width']);

		return $instance;
	}
}

/**
 * Pinterest Board Widget Class
 *
 * @since 0.2
 */
class pit_widget_board extends WP_Widget {
	// Constructor
	function __construct() {
		parent::__construct(
			'pit_widget_board', // Base ID
			__( 'Pinterest Board Widget', 'pit' ), // Name
			array( 'description' => __( 'Show up to 30 of your favorite board\'s latest pins.', 'pit' ) ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args. Widget arguments.
	 * @param array $instance. Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		$user = $instance['user'];
		$board = $instance['board'];
		$image_width = $instance['image_width'];
		$width = $instance['width'];
		$height = $instance['height'];

		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];

		$data_image_width = '';
		$data_width = '';
		$data_height = '';

		if ( ! empty( $image_width ) )
			$data_image_width = ' data-pin-scale-width="' . $image_width . '"';
		if ( ! empty( $height ) )
			$data_height = ' data-pin-scale-height="' . $height . '"';
		if ( ! empty( $width ) )
			$data_width = ' data-pin-board-width="' . $width . '"';

		if ( ! empty( $user ) && ! empty( $board ) )
			echo '<a data-pin-do="embedBoard" href="http://www.pinterest.com/' . $user . '/' . $board . '/"' . $data_image_width . $data_height . $data_width . '></a>';

		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance. Previously saved values from database.
	 */
	public function form( $instance ) {
		?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', 'pit' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>">
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('user'); ?>"><?php _e( 'Pinterest Username:', 'pit' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('user'); ?>" name="<?php echo $this->get_field_name('user'); ?>" type="text" value="<?php echo esc_attr( $instance['user'] ); ?>">
			<br>
			<small>http://www.pinterest.com/<strong>username</strong>/boardname/</small>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('board'); ?>"><?php _e( 'Board:', 'pit' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('board'); ?>" name="<?php echo $this->get_field_name('board'); ?>" type="text" value="<?php echo esc_attr( $instance['board'] ); ?>">
			<br>
			<small>http://www.pinterest.com/username/<strong>boardname</strong>/</small>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('image_width'); ?>"><?php _e( 'Image Width:', 'pit' ); ?></label>
			<input class="widefat pit-image-width" id="<?php echo $this->get_field_id('image_width'); ?>" name="<?php echo $this->get_field_name('image_width'); ?>" type="text" value="<?php echo esc_attr( $instance['image_width'] ); ?>">
			<br>
			<small><?php _e( 'min: 60; leave 0 or blank for 92', 'pit' ); ?></small>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('height'); ?>"><?php _e( 'Board Height:', 'pit' ); ?></label>
			<input class="widefat pit-height" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo esc_attr( $instance['height'] ); ?>">
			<br>
			<small><?php _e( 'min: 60; leave 0 or blank for 175', 'pit' ); ?></small>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('width'); ?>"><?php _e( 'Board Width:', 'pit' ); ?></label>
			<input class="widefat pit-width" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="text" value="<?php echo esc_attr( $instance['width'] ); ?>">
			<br>
			<small><?php _e( 'min: 130; leave 0 or blank for auto', 'pit' ); ?></small>
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
		$instance['user'] = $new_instance['user'];
		$instance['board'] = $new_instance['board'];
		$instance['image_width'] = intval($new_instance['image_width']);
		$instance['height'] = intval($new_instance['height']);
		$instance['width'] = intval($new_instance['width']);

		return $instance;
	}
}

/**
 * Pinterest Pin Widget Class
 *
 * @since 0.3
 */
class pit_widget_pin extends WP_Widget {
	// Constructor
	function __construct() {
		parent::__construct(
			'pit_widget_pin', // Base ID
			__( 'Pinterest Pin Widget', 'pit' ), // Name
			array( 'description' => __( 'Embed one of your pins on your site.', 'pit' ) ) // Args
		);
	}

	/**
	 * Front-end display of widget
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args. Widget arguments.
	 * @param rray $instance. Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		$id = $instance['id'];

		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];

		if ( ! empty( $id ) )
			echo '<a data-pin-do="embedPin" href="http://www.pinterest.com/pin/' . $id . '/"></a>';

		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance. Previously saved values from database.
	 */
	public function form( $instance ) {
		?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', 'pit' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>">
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('id'); ?>"><?php _e( 'Pin ID:', 'pit' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('id'); ?>" name="<?php echo $this->get_field_name('id'); ?>" type="text" value="<?php echo esc_attr( $instance['id'] ); ?>">
			<br>
			<small>http://www.pinterest.com/pin/<strong>pin_id</strong>/</small>
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
		$instance['id'] = $new_instance['id'];

		return $instance;
	}
}

/**
 * Register widgets
 */
function pit_register_widget() {
	register_widget('pit_widget_profile');
	register_widget('pit_widget_board');
	register_widget('pit_widget_pin');
}
add_action( 'widgets_init', 'pit_register_widget' );