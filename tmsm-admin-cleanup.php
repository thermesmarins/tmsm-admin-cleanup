<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/nicomollet
 * @since             1.0.0
 * @package           Tmsm_Admin_Cleanup
 *
 * @wordpress-plugin
 * Plugin Name:       TMSM Admin Cleanup
 * Plugin URI:        https://github.com/thermesmarins/tmsm-admin-cleanup
 * Description:       Customization and Cleanup of the admin for Thermes Marins de Saint-Malo
 * Version:           1.3.2
 * Author:            Nicolas Mollet
 * Author URI:        https://github.com/nicomollet
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       tmsm-admin-cleanup
 * Domain Path:       /languages
 * Github Plugin URI: https://github.com/thermesmarins/tmsm-admin-cleanup
 * Github Branch:     master
 * Requires PHP:      7.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'TMSM_ADMIN_CLEANUP_VERSION', '1.3.2' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-tmsm-admin-cleanup-install.php
 */
function activate_tmsm_admin_cleanup() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-tmsm-admin-cleanup-install.php';
	Tmsm_Woocommerce_Admin_Cleanup_Install::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-tmsm-admin-cleanup-install.php
 */
function deactivate_tmsm_admin_cleanup() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-tmsm-admin-cleanup-install.php';
	Tmsm_Woocommerce_Admin_Cleanup_Install::deactivate();
}

register_activation_hook( __FILE__, 'activate_tmsm_admin_cleanup' );
register_deactivation_hook( __FILE__, 'deactivate_tmsm_admin_cleanup' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-tmsm-admin-cleanup.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_tmsm_admin_cleanup() {

	$plugin = new Tmsm_Admin_Cleanup();
	$plugin->run();

}
run_tmsm_admin_cleanup();
