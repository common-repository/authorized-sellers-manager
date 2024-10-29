<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://brightroll.com
 * @since      1.0.0
 *
 * @package    Ads_Txt_Publisher
 * @subpackage Ads_Txt_Publisher/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Ads_Txt_Publisher
 * @subpackage Ads_Txt_Publisher/includes
 * @author     Brightroll <plugin@brightroll.com>
 */
class Ads_Txt_Publisher_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
        delete_option('ads-txt-publisher-hide-intro-message');
	}

}
