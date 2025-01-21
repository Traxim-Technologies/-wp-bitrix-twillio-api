<?php

/**
 *
 * @link              http://traximtech.com
 * @since             1.0.0
 * @package           Twillio_Mobile_Verification
 *
 * @wordpress-plugin
 * Plugin Name:       Twillio Mobile Verification
 * Plugin URI:        https://git.ttechcode.com/wordpress/plugins/twillio-mobile-verification
 * Description:       This plugin provide twillio-mobile verification.
 * Version:           1.0.0
 * Author:            Traxim Technologies
 * Author URI:        http://traximtech.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       twillio-mobile-verification
 * Domain Path:       /languages
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
define( 'TWILLIO_MOBILE_VERIFICATION_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-twillio-mobile-verification-activator.php
 */
function activate_twillio_mobile_verification() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-twillio-mobile-verification-activator.php';
	Twillio_Mobile_Verification_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-twillio-mobile-verification-deactivator.php
 */
function deactivate_twillio_mobile_verification() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-twillio-mobile-verification-deactivator.php';
	Twillio_Mobile_Verification_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_twillio_mobile_verification' );
register_deactivation_hook( __FILE__, 'deactivate_twillio_mobile_verification' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-twillio-mobile-verification.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_twillio_mobile_verification() {

	$plugin = new Twillio_Mobile_Verification();
	$plugin->run();

}
run_twillio_mobile_verification();
