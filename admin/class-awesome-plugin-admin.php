<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.webfreelance.eu
 * @since      1.0.0
 *
 * @package    Awesome_Plugin
 * @subpackage Awesome_Plugin/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Awesome_Plugin
 * @subpackage Awesome_Plugin/admin
 * @author     Konstantinos Kariyiannis <karugianni@gmail.com>
 */
class Awesome_Plugin_Admin {

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

	//private $awesome_plugin_settings_options;  
	
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
  }

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/awesome-plugin-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/awesome-plugin-admin.js', array( 'jquery' ), $this->version, false );

	}

    public function awesome_plugin_settings_add_plugin_page() {
		add_menu_page(
			'Awesome Plugin Settings', // page_title
			'Awesome Plugin Settings', // menu_title
			'manage_options', // capability
			'awesome-plugin-settings', // menu_slug
			array( $this, 'awesome_plugin_settings_create_admin_page' ), // function
			'dashicons-schedule', // icon_url
			80 // position
		);
	}

	public function awesome_plugin_settings_create_admin_page() {
		$this->awesome_plugin_settings_options = get_option( 'awesome_plugin_settings_option_name' ); ?>

		<div class="wrap">
			<h2>Awesome Plugin Settings</h2>
			<p>Set Api Key, to get your users to display random information about games.</p>
			<p>The Api we are hooking to is this: <a href="https://rapidapi.com/digiwalls/api/free-to-play-games-database/" target="_blank">RapidApi Free-to-Play Games Database </a> </p>
			<p>For Demonstration Purposes, we are using the key : <b>347f2b29e8mshd562d3f6589dcd4p1e5afdjsn2817b49a2bdc</b></p>
			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php
					settings_fields( 'awesome_plugin_settings_option_group' );
					do_settings_sections( 'awesome-plugin-settings-admin' );
					submit_button();
				?>
			</form>
		</div>
	<?php }

	public function awesome_plugin_settings_page_init() {
		register_setting(
			'awesome_plugin_settings_option_group', // option_group
			'awesome_plugin_settings_option_name', // option_name
			array( $this, 'awesome_plugin_settings_sanitize' ) // sanitize_callback
		);

		add_settings_section(
			'awesome_plugin_settings_setting_section', // id
			'Settings', // title
			array( $this, 'awesome_plugin_settings_section_info' ), // callback
			'awesome-plugin-settings-admin' // page
		);

		add_settings_field(
			'api_key_0', // id
			'Api Key ', // title
			array( $this, 'api_key_0_callback' ), // callback
			'awesome-plugin-settings-admin', // page
			'awesome_plugin_settings_setting_section' // section
		);
	}

	public function awesome_plugin_settings_sanitize($input) {
		$sanitary_values = array();
		if ( isset( $input['api_key_0'] ) ) {
			$sanitary_values['api_key_0'] = sanitize_text_field( $input['api_key_0'] );
		}

		return $sanitary_values;
	}

	public function awesome_plugin_settings_section_info() {
		
	}

	public function api_key_0_callback() {
		printf(
			'<input class="regular-text" type="text" name="awesome_plugin_settings_option_name[api_key_0]" id="api_key_0" value="%s">',
			isset( $this->awesome_plugin_settings_options['api_key_0'] ) ? esc_attr( $this->awesome_plugin_settings_options['api_key_0']) : ''
		);
	}

   public function custom_user_profile_fields($user){
	if(is_object($user))
	{
		$platform = esc_attr( get_the_author_meta( 'platform', $user->ID ) );
		$category = esc_attr( get_the_author_meta( 'category', $user->ID ) );
	}
	else
	{
		$platform = null;
		$category = null;
	}
?>
<h2>Game Filters<h2>

<table class="form-table">
 <tr>
  <th><label for="platform"><?php _e("Game Platform"); ?></th>
  <td><input type="text" class="regular-text" name="platform" value="<?php echo $platform;?>" id="platform" /><br/>
    <span class="description"><?php _e("Please enter the platform of your choosing."); ?></span>
  </td> 
 </tr>
 <tr>
  <th><label for="category"><?php _e("Game Category"); ?></th>
  <td><input type="text" class="regular-text" name="category" value="<?php echo $category;?>" id="category" /><br/>
    <span class="description"><?php _e("Please enter the category of your choosing."); ?></span>
  </td> 
 </tr>
</table>	

<?php
}

public function save_custom_user_profile_fields($user_id){
	// again do this only if you can
	if(!current_user_can('manage_options'))
	return false;
	 
	// save my custom field
	update_user_meta($user_id, 'platform', $_POST['platform']);
	update_user_meta($user_id, 'category', $_POST['category']);
	
}


}
