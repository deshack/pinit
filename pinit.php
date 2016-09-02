<?php

/**
 * Pinit: Pinterest for WordPress
 *
 * @package   Pinit
 * @author    Eugenio Petullà <support@codeat.co>
 * @license   GPL-2.0+
 * @link      http://codeat.co/pinit
 * @copyright 2016 Mattia Migliorini e Eugenio Petullà
 *
 * @wordpress-plugin
 * Plugin Name:       Pinit
 * Plugin URI:        http://codeat.co/pinit
 * Description: Handy plugin that adds Pinterest Follow Button, Pin Widget, Profile Widget and Board Widget to your WordPress site.
 * Version:           2.1.1
 * Author:            deshack
 * Author URI:        https://github.com/deshack/
 * Text Domain:       pinit
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
	die;
}

/*
 * ------------------------------------------------------------------------------
 * Public-Facing Functionality
 * ------------------------------------------------------------------------------
 */
require_once( plugin_dir_path( __FILE__ ) . 'includes/load_textdomain.php' );


require_once( plugin_dir_path( __FILE__ ) . 'public/class-pinit.php' );


/*
 * - 9999 is used for load the plugin as last for resolve some
 *   problems when the plugin use API of other plugins, remove
 *   if you don' want this
 */

add_action( 'plugins_loaded', array( 'Pinit', 'get_instance' ), 9999 );

require_once( plugin_dir_path( __FILE__ ) . 'includes/widgets/widgets.php' );

/*
 * -----------------------------------------------------------------------------
 * Dashboard and Administrative Functionality
 * -----------------------------------------------------------------------------
*/

/*
 *
 * If you want to include Ajax within the dashboard, change the following
 * conditional to:
 *
 * if ( is_admin() ) {
 *   ...
 * }
 *
 */

if ( is_admin() && (!defined( 'DOING_AJAX' ) || !DOING_AJAX ) ) {
	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-pinit-admin.php' );
	add_action( 'plugins_loaded', array( 'Pinit_Admin', 'get_instance' ) );
}
