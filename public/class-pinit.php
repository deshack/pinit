<?php

/**
 * Pinit.
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
	const VERSION = '2.1.0';

	/**
	 * Unique identifier for your plugin.
	 *
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected static $plugin_slug = 'pinit';

	/**
	 * Unique identifier for your plugin.
	 *
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected static $plugin_name = 'Pinit';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;


	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0.0
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
	}

	/**
	 * Return the plugin slug.
	 *
	 * @since    1.0.0
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return self::$plugin_slug;
	}

	/**
	 * Return the plugin name.
	 *
	 * @since    1.0.0
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
	 * @since     1.0.0
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
     * Here is where magic happens...
     * Read the settings and render the proper pinit.js
     * 
     * @return string
     */

	public function pit_pinit_js() {
		if( isset( $this->settings[ 'on_hover' ] )){
			$yoda = ' data-pin-hover="true"';

			if( isset( $this->settings[ 'round' ] )) {
				$yoda .= ' data-pin-round="true"';
			}

			elseif ( !isset( $this->settings[ 'round' ] )) {

				if( isset( $this->settings[ 'color' ] )){
					$yoda .= ' data-pin-color="'. $this->settings[ 'color' ] . '"';
				}

				if( isset( $this->settings[ 'language' ] )){
					$yoda .= ' data-pin-lang="'. $this->settings[ 'language' ] . '"';
				}
			}

			if( isset( $this->settings[ 'large' ] )) {
				$yoda .= ' data-pin-tall="true"';
			}


		}

		else {
			$yoda = '';
		}

		echo '<script async defer' . $yoda . ' type="text/javascript" async src="//assets.pinterest.com/js/pinit.js"></script>' . "\n";
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
