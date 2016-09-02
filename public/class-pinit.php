<?php

/**
 * Pinit: Pinterest for WordPress
 *
 * @package   Pinit
 * @author    Eugenio Petullà <support@codeat.co>
 * @license   GPL-2.0+
 * @link      http://codeat.co/pinit
 * @copyright 2016 Mattia Migliorini e Eugenio Petullà
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * public-facing side of the WordPress site.
 *
 * If you're interested in introducing administrative or dashboard
 * functionality, then refer to `class-pinit-admin.php`
 *
 * @package Pinit
 * @author  Eugenio Petullà <support@codeat.co>
 */
class Pinit {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	const VERSION = '2.1.1';

	/**
	 * Unique identifier for your plugin.
	 *
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @since    2.1.0
	 *
	 * @var      string
	 */
	protected static $plugin_slug = 'pinit';

	/**
	 * Unique identifier for your plugin.
	 *
	 *
	 * @since    2.1.0
	 *
	 * @var      string
	 */
	protected static $plugin_name = 'Pinit';

	/**
	 * Instance of this class.
	 *
	 * @since    2.1.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	protected $settings = null;


	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     2.1.0
	 */
	private function __construct() {

		/*
		 * Define custom functionality.
		 * Refer To http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		 */
		add_action( 'wp_footer', array( $this, 'pit_pinit_js' ), 9999 );

		add_shortcode( 'pit-follow', array( $this, 'pit_follow_shortcode' ) );
		add_shortcode( 'pit-pin', array( $this, 'pit_pin_shortcode' ) );
		add_shortcode( 'pit-profile', array( $this, 'pit_profile_shortcode' ) );
		add_shortcode( 'pit-board', array( $this, 'pit_board_shortcode' ) );
		//* Get the General settings
		$this->settings = get_option( $this->get_plugin_slug() . '-settings' );

		/**
		 * Register widgets
		 */
		add_action( 'widgets_init', create_function('', 'return register_widget( "pit_pinterest" );') );
	}

	/**
	 * Return the plugin slug.
	 *
	 * @since    2.1.0
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return self::$plugin_slug;
	}

	/**
	 * Return the plugin name.
	 *
	 * @since    2.1.0
	 *
	 * @return    Plugin name variable.
	 */
	public function get_plugin_name() {
		return self::$plugin_name;
	}

	/**
	 * Return the version
	 *
	 * @since    1.0.0
	 *
	 * @return    Version const.
	 */
	public function get_plugin_version() {
		return self::VERSION;
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     2.1.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
     * Print pinit.js
     * 
     * @since  1.0.0
     * @return string
     */

	public function pit_pinit_js() {

		if( !empty( $this->settings[ 'single_post' ] ) && !empty( $this->settings[ 'single_page' ] ) ) {
			if( is_singular('post') || is_singular('page') && !is_front_page()) {
				$this->pinit_html_data( true );
			}

			else {
				$this->pinit_html_data( false );
			}
		}
		elseif( !empty( $this->settings[ 'single_page' ] ) && empty( $this->settings[ 'single_post' ] ) ) {
			if( is_singular('page') && !is_front_page()) {
				$this->pinit_html_data( true );
			}

			else {
				$this->pinit_html_data( false );
			}
		}
		elseif( !empty( $this->settings[ 'single_post' ] ) && empty( $this->settings[ 'single_page' ] ) ) {
			if( is_singular('post')) {
				$this->pinit_html_data( true );
			}

			else {
				$this->pinit_html_data( false );
			}
		}
		else {
			$this->pinit_html_data( true );
		}
	}


	/**
     * Here is where magic happens...
     * Read the settings and render the proper pinit.js
     * 
     * @since 2.1.0
     * @param bool $active
     * @return string
     */
	public function pinit_html_data( $active ){
		if ( $active == true ) {
			if( !empty( $this->settings[ 'on_hover' ] )){
				$yoda = ' data-pin-hover="true"';

				if( !empty( $this->settings[ 'round' ] )) {
					$yoda .= ' data-pin-round="true"';
				}

				else {

					if( isset( $this->settings[ 'language' ] )){
						$yoda .= ' data-pin-lang="'. $this->settings[ 'language' ] . '"';
					}
				}

				if( !empty( $this->settings[ 'large' ] )) {
					$yoda .= ' data-pin-tall="true"';
				}


			}

			else {
				$yoda = '';
			}

			echo '<script async defer' . $yoda . ' type="text/javascript" async src="//assets.pinterest.com/js/pinit.js"></script>' . "\n";
		}
		else {

			echo '<script async defer type="text/javascript" async src="//assets.pinterest.com/js/pinit.js"></script>' . "\n";
		}
	}

	/**
	 * NOTE:  Shortcode simple set of functions for creating macro codes for use
	 * 		  in post content.
	 *
	 *        Reference:  http://codex.wordpress.org/Shortcode_API
	 *
	 * @since    1.0.0
	 */

	public function pit_follow_shortcode( $atts ) {
		$atts = extract( shortcode_atts( array(
			'url' => 'http://www.pinterest.com/pinterest/',
			'text' => 'Follow',
		), $atts ) );

		return sprintf('<a data-pin-do="buttonFollow" href="%1$s">%2$s</a>', $url, $text );
	}

	public function pit_pin_shortcode( $atts ) {
		$atts = extract( shortcode_atts( array(
			'url' => 'http://www.pinterest.com/pin/99360735500167749/',
			'size' => 'small',
		), $atts ) );

		$width = ( $size == 'large' || $size == 'medium' ) ? sprintf( 'data-pin-width="%s" ', $size ) : '';
		
		return sprintf( '<a data-pin-do="embedPin" %1$s href="%2$s"></a>', $width, $url );
	}

	public function pit_profile_shortcode( $atts ) {
		$atts = extract( shortcode_atts( array(
			'url' => 'https://www.pinterest.com/pinterest/',
			'imgwidth' => '80',
			'boxheight' => '400',
			'boxwidth' => '400',
		), $atts ) );

		return sprintf( 
			'<a data-pin-do="embedUser" data-pin-board-width="%1$s" data-pin-scale-height="%2$s" data-pin-scale-width="%3$s" href="%4$s"></a>',
			$boxwidth,
			$boxheight,
			$imgwidth,
			$url
		);
	}

	public function pit_board_shortcode( $atts ) {
		$atts = extract( shortcode_atts( array(
			'url' => 'https://www.pinterest.com/pinterest/pin-tips/',
			'imgwidth' => '80',
			'boxheight' => '400',
			'boxwidth' => '400',
		), $atts ) );

	return sprintf(
			'<a data-pin-do="embedBoard" data-pin-board-width="%1$s" data-pin-scale-height="%2$s" data-pin-scale-width="%3$s" href="%4$s"></a>',
			$boxwidth,
			$boxheight,
			$imgwidth,
			$url
		);
	}

}
