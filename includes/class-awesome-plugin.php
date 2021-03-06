<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.webfreelance.eu
 * @since      1.0.0
 *
 * @package    Awesome_Plugin
 * @subpackage Awesome_Plugin/includes
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
 * @package    Awesome_Plugin
 * @subpackage Awesome_Plugin/includes
 * @author     Konstantinos Kariyiannis <karugianni@gmail.com>
 */
class Awesome_Plugin {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Awesome_Plugin_Loader    $loader    Maintains and registers all hooks for the plugin.
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
		if ( defined( 'AWESOME_PLUGIN_VERSION' ) ) {
			$this->version = AWESOME_PLUGIN_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'awesome-plugin';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	    
		 
		
      }
       
	 

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Awesome_Plugin_Loader. Orchestrates the hooks of the plugin.
	 * - Awesome_Plugin_i18n. Defines internationalization functionality.
	 * - Awesome_Plugin_Admin. Defines all hooks for the admin area.
	 * - Awesome_Plugin_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-awesome-plugin-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-awesome-plugin-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-awesome-plugin-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-awesome-plugin-public.php';

		$this->loader = new Awesome_Plugin_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Awesome_Plugin_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Awesome_Plugin_i18n();

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

		$plugin_admin = new Awesome_Plugin_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'awesome_plugin_settings_add_plugin_page' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'awesome_plugin_settings_page_init' );
	
	//add custom user profile fields

	$this->loader->add_action( 'show_user_profile', $plugin_admin, 'custom_user_profile_fields' );
	$this->loader->add_action( 'edit_user_profile', $plugin_admin, 'custom_user_profile_fields' );
	//$this->loader->add_action( 'user_new_form', $plugin_admin, 'custom_user_profile_fields' );
	$this->loader->add_action( 'personal_options_update', $plugin_admin, 'save_custom_user_profile_fields' );
	$this->loader->add_action( 'edit_user_profile_update', $plugin_admin, 'save_custom_user_profile_fields' );
    
	

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Awesome_Plugin_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		
		
		// add custom fields to user account on the frontend

		$this->loader->add_action( 'woocommerce_edit_account_form', $plugin_public, 'add_fields_in_account_form' );
		$this->loader->add_action( 'woocommerce_save_account_details', $plugin_public, 'save_account_details' );
		
		// add a custom section in woocommerce user

		$this->loader->add_action( 'init', $plugin_public, 'awesome_woo_add_endpoint' );
		$this->loader->add_filter( 'query_vars', $plugin_public, 'awesome_woo_query_vars', 0 );
		$this->loader->add_filter( 'woocommerce_account_menu_items', $plugin_public, 'awesome_woo_add_link_my_account' );
        $this->loader->add_action( 'woocommerce_account_games_endpoint', $plugin_public, 'awesome_woo_content' );
	    
		// shortcodes
		$this->loader->add_action( 'init', $plugin_public, 'register_shortcodes' );
	    
		//add shortcode capability in widgets
		$this->loader->add_filter( 'widget_text', $plugin_public, 'do_shortcode' );
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
	 * @return    Awesome_Plugin_Loader    Orchestrates the hooks of the plugin.
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
