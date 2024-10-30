<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.clicksports.de
 * @since             1.0.0
 * @package           Clicksports_Maps
 *
 * @wordpress-plugin
 * Plugin Name:       CLICKSPORTS Maps
 * Description:       This plugin is a simple alternative to Google Maps and displays a customizable map on your website.
 * Version:           1.4.0
 * Author:            CLICKSPORTS
 * Author URI:        https://www.clicksports.de
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       clicksports-maps
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Dummy translation string for the plugin's description.
$translation_description = esc_html__( 'This plugin is a simple alternative to Google Maps and displays a customizable map on your website.', 'clicksports-maps' );

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'CLICKSPORTS_MAPS_VERSION', '1.4.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-clicksports-maps-activator.php
 */
function activate_clicksports_maps() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-clicksports-maps-activator.php';
	Clicksports_Maps_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-clicksports-maps-deactivator.php
 */
function deactivate_clicksports_maps() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-clicksports-maps-deactivator.php';
	Clicksports_Maps_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_clicksports_maps' );
register_deactivation_hook( __FILE__, 'deactivate_clicksports_maps' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-clicksports-maps.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_clicksports_maps() {

	$plugin = new Clicksports_Maps();
	$plugin->run();

}
run_clicksports_maps();

?>
