<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link               http://www.brightcom.com/
 * @since             1.0.0
 * @package           Ads.txt Publisher
 *
 * @wordpress-plugin
 * Plugin Name:       Ads.txt Publisher
 * Plugin URI:        http://www.brightcom.com/
 * Description:       Ads.txt Publisher lets you easily edit and publish your ads.txt file. Create your file, edit it, check for errors and publish - all with ease. No dev skills needed.
 * Version:           1.0.16
 * Author:            Brightcom
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ads-txt-publisher
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
define( 'ADSTXTPUB_PLUGIN_NAME_VERSION', '1.0.16' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ads-txt-publisher-activator.php
 */
function activate_ads_txt_publisher() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ads-txt-publisher-activator.php';
	Ads_Txt_Publisher_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ads-txt-publisher-deactivator.php
 */
function deactivate_ads_txt_publisher() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ads-txt-publisher-deactivator.php';
	Ads_Txt_Publisher_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_ads_txt_publisher' );
register_deactivation_hook( __FILE__, 'deactivate_ads_txt_publisher' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ads-txt-publisher.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ads_txt_publisher() {

	$plugin = new Ads_Txt_Publisher();
	$plugin->run();

}
run_ads_txt_publisher();
