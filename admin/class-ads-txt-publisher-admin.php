<?php
global $menu;

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://brightroll.com
 * @since      1.0.0
 *
 * @package    Ads_Txt_Publisher
 * @subpackage Ads_Txt_Publisher/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ads_Txt_Publisher
 * @subpackage Ads_Txt_Publisher/admin
 * @author     Brightroll <plugin@brightroll.com>
 */
class Ads_Txt_Publisher_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
    public function enqueue_styles()
    {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/adstxtpublisher-admin.css', array(), $this->version, 'all');
        wp_enqueue_style('highlight-within-textarea', 'https://cdn.jsdelivr.net/npm/highlight-within-textarea@2.0.4/jquery.highlight-within-textarea.css', array(), $this->version, 'all');
    }

		/**
     * Register the JavaScript for the admin area.
		 *
     * @since    1.0.0
		 */
    public function enqueue_scripts()
    {
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/adstxtpublisher-admin.js', array('jquery'), $this->version, false);
        wp_enqueue_script('highlight-within-textarea', plugin_dir_url(__FILE__) . 'js/jquery.highlight-within-textarea.js', array('jquery'), $this->version, true);
        wp_enqueue_script('sweetalert', 'https://cdn.jsdelivr.net/npm/sweetalert@2.1.0/dist/sweetalert.min.js', array('jquery'), $this->version, true);
    }

    public function redirect_from_dashboard_to_plugin_for_ads_txt_publisher_user() {
        global $pagenow;
        $user = wp_get_current_user();

        if (in_array('ads_txt_publisher', $user->roles) && $pagenow === 'index.php') {
            wp_redirect(admin_url('/admin.php?page=ads-txt-publisher', 'http'), 301);
            exit;
        }
    }

    public function add_body_class($classes) {
        return $classes . ' adstxt-publisher';
    }

    public function hide_menu_items_for_ads_txt_publisher_user() {
        global $menu;

        $user = wp_get_current_user();

        if (in_array('ads_txt_publisher', $user->roles)) {
            foreach ($menu as $element) {
                if (!in_array($element[2], array('profile.php', 'ads-txt-publisher'))) {
                    remove_menu_page($element[2]);
                }
            }
        }
    }


    public function register_menu_page()
    {

        add_menu_page($this->plugin_name, 'Ads.txt Publisher', 'read', 'ads-txt-publisher', array($this, 'include_admin_page'));
    }


    function include_admin_page()
    {
    	$ads_txt_plugin_url = plugin_dir_url(__FILE__);

        include 'partials/ads-txt-publisher-admin-display.php';
    }

    function  handle_closing_intro_message() {
        header('Content-Type: application/json');

        update_option('ads-txt-publisher-hide-intro-message', true);

        echo $this->prepareResponse(true, 1, 'Intro message will not showed anymore');
    }

    function handle_create_file()
    {
        header('Content-Type: application/json');

        $file_path = get_home_path() . 'ads.txt';
        $ads_file_writable = is_writable(get_home_url());

        if (true) {
            fopen($file_path, 'w');
	        file_put_contents($file_path, "# Created by Ads.txt Publisher by Brightcom\n");

            echo $this->prepareResponse(true, 1, 'The file is created successfully');
        } else {
            echo $this->prepareResponse(false, -1, 'Can\'t create file');
        }
    }

    function handle_add_user()
    {
        header('Content-Type: application/json');

        try {
            if (empty($_POST) || !$_POST['user_email']) {
                throw new Exception('Email can\'t be empty.');
            }

            $user_email = $_POST['user_email'];

            if (email_exists($user_email)) {
                throw new Exception('User with this email already exists');
            }

            $random_password = wp_generate_password($length = 12, $include_standard_special_chars = false);

            $user_id = wp_create_user($user_email, $random_password, $user_email);

            $user_data = get_userdata($user_id);

            $user_data->set_role('ads_txt_publisher');

            wp_update_user($user_data);

            wp_new_user_notification($user_id);

            $subject = 'Authorized Sellers Manager - User created';

            ob_start();
            include_once(plugin_dir_path(__FILE__) . 'partials/ads-txt-publisher-new-user-email.php');
            $message = ob_get_contents();
            $headers = 'Content-Type: text/html; charset=UTF-8';
            ob_end_clean();

            $user_result = wp_mail($user_email, $subject, $message, $headers);

            

            echo $this->prepareResponse(true, 1, 'The user is created successfully');

        } catch (Exception $e) {
            echo $this->prepareResponse(false, -1, $e->getMessage());
        }
    }

    function handle_save_ads_file()
    {
        header('Content-Type: application/json');
        $file_path = get_home_path() . 'ads.txt';
        $ads_file_writable = is_writable($file_path);

        try {
            if (empty($_POST) || !isset($_POST['ads_file'])) {
                throw new Exception('Something went wrong');
            }

            if (!$ads_file_writable) {
                throw new Exception('Failed to open stream: Permission denied (' . $file_path . ').');
	}

            file_put_contents($file_path, $_POST['ads_file']);

            echo $this->prepareResponse(true, 1, 'Ads.txt has been saved successfully');

        } catch (Exception $e) {
            echo $this->prepareResponse(false, -1, $e->getMessage());
        }
    }

    function prepareResponse($status, $code, $message, $value = null)
    {
        if (!$status) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
        }

        $result = array(
            "status" => $status,
            "code" => $code,
            "message" => $message,
        );

        if ($value) {
            $result['value'] = $value;
	}

        return json_encode($result);
    }
}
