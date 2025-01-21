<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://traximtech.com
 * @since      1.0.0
 *
 * @package    Twillio_Mobile_Verification
 * @subpackage Twillio_Mobile_Verification/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Twillio_Mobile_Verification
 * @subpackage Twillio_Mobile_Verification/includes
 * @author     Traxim Technologies <info@traximtech.com>
 */
class Twillio_Mobile_Verification_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'twillio-mobile-verification',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
