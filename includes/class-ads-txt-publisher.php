<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://brightroll.com
 * @since      1.0.0
 *
 * @package    Ads_Txt_Publisher
 * @subpackage Ads_Txt_Publisher/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Ads_Txt_Publisher
 * @subpackage Ads_Txt_Publisher/includes
 * @author     Brightroll <plugin@brightroll.com>
 */
class Ads_Txt_Publisher {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Ads_Txt_Publisher_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'ADSTXTPUB_PLUGIN_NAME_VERSION' ) ) {
			$this->version = ADSTXTPUB_PLUGIN_NAME_VERSION;
		} else {
			$this->version = '1.0.12';
		}
		$this->plugin_name = 'ads-txt-publisher';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Ads_Txt_Publisher_Loader. Orchestrates the hooks of the plugin.
	 * - Ads_Txt_Publisher_i18n. Defines internationalization functionality.
	 * - Ads_Txt_Publisher_Admin. Defines all hooks for the admin area.
	 * - Ads_Txt_Publisher_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ads-txt-publisher-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ads-txt-publisher-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-ads-txt-publisher-admin.php';


		$this->loader = new Ads_Txt_Publisher_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Ads_Txt_Publisher_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Ads_Txt_Publisher_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Ads_Txt_Publisher_Admin( $this->get_plugin_name(), $this->get_version() );

        $this->loader->add_action('admin_init', $plugin_admin, 'redirect_from_dashboard_to_plugin_for_ads_txt_publisher_user');
        $this->loader->add_action('admin_menu', $plugin_admin, 'hide_menu_items_for_ads_txt_publisher_user', 9999);
		$this->loader->add_action('admin_menu', $plugin_admin, 'register_menu_page');
		$this->loader->add_action('admin_post_handle_closing_intro_message', $plugin_admin, 'handle_closing_intro_message');
		$this->loader->add_action('admin_post_handle_add_user', $plugin_admin, 'handle_add_user');
		$this->loader->add_action('admin_post_handle_save_ads_file', $plugin_admin, 'handle_save_ads_file');
		$this->loader->add_action('admin_post_handle_create_file', $plugin_admin, 'handle_create_file');
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_body_class', $plugin_admin, 'add_body_class' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Ads_Txt_Publisher_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
