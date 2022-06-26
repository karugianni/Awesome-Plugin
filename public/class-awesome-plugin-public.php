<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.webfreelance.eu
 * @since      1.0.0
 *
 * @package    Awesome_Plugin
 * @subpackage Awesome_Plugin/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Awesome_Plugin
 * @subpackage Awesome_Plugin/public
 * @author     Konstantinos Kariyiannis <karugianni@gmail.com>
 */
class Awesome_Plugin_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Awesome_Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Awesome_Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/awesome-plugin-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Awesome_Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Awesome_Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/awesome-plugin-public.js', array( 'jquery' ), $this->version, false );

	}

   public function awesome_woo_add_endpoint() {
		add_rewrite_endpoint( 'games', EP_ROOT | EP_PAGES );
	}
	
   public function awesome_woo_query_vars( $vars ) {
		$vars[] = 'games';
		return $vars;
	}  	

   public function awesome_woo_add_link_my_account( $items ) {
		$items['games'] = 'View Games Info';
		return $items;
	}
	
	// 4. Add content to the new endpoint  
    public function awesome_woo_content() {
	  
		echo '<h3>View your chosen games</h3><p>This is a special section, to display games, based upon your account filters. If the filters are not chosen, then you see the results based on default filters.</p>';
	
	}  

}
