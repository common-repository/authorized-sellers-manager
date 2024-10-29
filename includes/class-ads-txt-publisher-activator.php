<?php

/**
 * Fired during plugin activation
 *
 * @link       http://brightroll.com
 * @since      1.0.0
 *
 * @package    Ads_Txt_Publisher
 * @subpackage Ads_Txt_Publisher/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Ads_Txt_Publisher
 * @subpackage Ads_Txt_Publisher/includes
 * @author     Brightroll <plugin@brightroll.com>
 */
class Ads_Txt_Publisher_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */

	public static function add_credentials() {
		$file_path = get_home_path() . 'ads.txt';

		if (file_exists($file_path)) {
			$ads_file = file_get_contents($file_path);

			preg_match("/Created by Ads\.txt Publisher/", $ads_file, $matches);

			if (!$matches) {
				$ads_file = "# Created by Ads.txt Publisher by Brightcom \n" . $ads_file;
				file_put_contents($file_path,  $ads_file);
			}
		}
	}

	public static function activate() {
        add_role('ads_txt_publisher', 'Ads.txt Publisher', array(
            'read'=>true
        ));

        self::add_credentials();
	}
}
