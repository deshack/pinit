<?php
/**
 * Plugin Name: Pinit
 * Plugin URI: https://github.com/deshack/pinit
 * Description: Handy plugin that adds Pinterest Follow Button, Pin Widget, Profile Widget and Board Widget to your WordPress site.
 * Author: deshack
 * Version: 1.0
 * Author URI: http://www.deshack.net
 * License: GPLv2 or later
 */

/*=== SETUP
 *==============================*/

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

/*=== SHORTCODES
 *==============================*/

function pit_pin_shortcode( $atts ) {
	$atts = extract( shortcode_atts( array(
		'url' => 'http://www.pinterest.com/pin/99360735500167749/',
	), $atts ) );

	return '<a data-pin-do="embedPin" href="' . $url . '"></a>';
}

function pit_profile_shortcode( $atts ) {
	$atts = extract( shortcode_atts( array(
		'url' => 'http://www.pinterest.com/pinterest/',
		'imgWidth' => '92',
		'boxHeight' => '175',
		'boxWidth' => 'auto'
	), $atts ) );

	return '<a data-pin-do="embedUser" href="' . $url . '" data-pin-scale-width="' . $imgWidth . '" data-pin-scale-height="' . $boxHeight . '" data-pin-board-width="' . $boxWidth . '"></a>';
}

function pit_board_shortcode( $atts ) {
	$atts = extract( shortcode_atts( array(
		'url' => 'http://www.pinterest.com/pinterest/pin-pets/',
		'imgWidth' => '92',
		'boxHeight' => '175',
		'boxWidth' => 'auto'
	), $atts ) );

	return '<a data-pin-do="embedBoard" href="' . $url . '" data-pin-scale-width="' . $imgWidth . '" data-pin-scale-height="' . $boxHeight . '" data-pin-board-width="' . $boxWidth . '"></a>';
}

add_shortcode( 'pit-pin', 'pit_pin_shortcode' );
add_shortcode( 'pit-profile', 'pit_profile_shortcode' );
add_shortcode( 'pit-board', 'pit_board_shortcode' );

/*=== WIDGET
 *==============================*/

/**
 * Pinterest Profile Widget Class
 *
 * @since 0.1
 */
class pit_pinterest extends WP_Widget {
	// Constructor
	function __construct() {
		parent::__construct(
			'pit_pinterest', // Base ID
			__( 'Pinterest Widget', 'pit' ), // Name
			array( 'description' => __( 'Show Pit, Profile or Board Widget.', 'pit' ) ) // Args
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

		// URL
		if ( ! empty( $purl ) )
			$url = $purl;
		else
			$url = '';

		// Image Width
		if ( ! empty( $imgWidth ) )
			$width = $imgWidth; // Custom value
		else
			$width = '92'; // Default value

		// Board Height
		if ( ! empty( $boxHeight ) )
			$boardHeight = $boxHeight;
		else
			$boardHeight = '175';

		// Board Width
		if ( ! empty( $boxWidth ) )
			$boardWidth = $boxWidth;
		else
			$boardWidth = 'auto';

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

		if ( ! empty( $url ) )
			echo '<a data-pin-do="' . $ptype . '" href="' . $url . '" data-pin-scale-width="' . $width . '" data-pin-scale-height="' . $boardHeight . '" data-pin-board-width="' . $boardWidth . '"></a>';

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

		$title = esc_attr($instance['title']);			// Widget Title
		$purl = esc_attr($instance['purl']);			// Target URL
		$imgWidth = esc_attr($instance['imgWidth']);	// Image Width
		$boxHeight = esc_attr($instance['boxHeight']);	// Board Height
		$boxWidth = esc_attr($instance['boxWidth']);	// Board Width
		$select = esc_attr($instance['select']);		// Widget Type Selector
		if ( empty( $select ) )
			$select = 'pin';
		?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', 'pit' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>">
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('select'); ?>"><?php _e( 'Type:', 'pit' ); ?></label>
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

		<p>
			<label for="<?php echo $this->get_field_id('purl'); ?>">
				<?php printf( __( 'Pinterest %1$s URL:', 'pit' ), ucfirst($select) ); ?>
			</label>
			<input class="widefat" id="<?php echo $this->get_field_id('purl'); ?>" name="<?php echo $this->get_field_name('purl'); ?>" type="text" value="<?php echo $purl; ?>">
			<br>
			<small>
				<?php switch ($select) {
					case 'profile':
						echo 'http://www.pinterest.com/username/';
						break;
					case 'board':
						echo 'http://www.pinterest.com/username/boardname/';
						break;
					default:
						echo 'http://www.pinterest.com/pin_id/';
						break;
				} ?>
			</small>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('imgWidth'); ?>"><?php _e( 'Image Width:', 'pit' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('imgWidth'); ?>" name="<?php echo $this->get_field_name('imgWidth'); ?>" type="text" value="<?php echo $imgWidth; ?>">
			<br>
			<small><?php _e( 'min: 60; leave 0 or blank for 92', 'pit' ); ?></small>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('boxHeight'); ?>"><?php _e( 'Board Height:', 'pit' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('boxHeight'); ?>" name="<?php echo $this->get_field_name('boxHeight'); ?>" type="text" value="<?php echo $boxHeight; ?>">
			<br>
			<small><?php _e( 'min: 60; leave 0 or blank for 175', 'pit' ); ?></small>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('boxWidth'); ?>"><?php _e( 'Board Width:', 'pit' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('boxWidth'); ?>" name="<?php echo $this->get_field_name('boxWidth'); ?>" type="text" value="<?php echo $boxWidth; ?>">
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
		$instance['purl'] = $new_instance['purl'];
		$instance['imgWidth'] = $new_instance['imgWidth'];
		$instance['boxHeight'] = $new_instance['boxHeight'];
		$instance['boxWidth'] = $new_instance['boxWidth'];
		$instance['select'] = $new_instance['select'];

		return $instance;
	}
}

/**
 * Register widgets
 */
add_action( 'widgets_init', create_function('', 'return register_widget( "pit_pinterest" );') );