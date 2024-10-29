<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://brightroll.com
 * @since      1.0.0
 *
 * @package    Ads_Txt_Publisher
 * @subpackage Ads_Txt_Publisher/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Ads_Txt_Publisher
 * @subpackage Ads_Txt_Publisher/includes
 * @author     Brightroll <plugin@brightroll.com>
 */
class Ads_Txt_Publisher_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'ads-txt-publisher',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
