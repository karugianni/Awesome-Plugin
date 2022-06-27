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
	  
		echo '<h3>View your chosen games</h3><p>This is a special section, to display games, based upon your account filters. If the filters are blank, then you will see no games.</p>';
	    echo do_shortcode( '[games_query_call]' );
	} 

	public function add_fields_in_account_form() {
		
		woocommerce_form_field(
          'platform',
		  array (
		  'type' => 'text',
		  'required' => false,
		  'label' => _e('Games Platform')	  
		  ),
		  get_user_meta( get_current_user_id(), 'platform', true )
		);

		woocommerce_form_field(
			'category',
			array (
			'type' => 'text',
			'required' => false,
			'label' => _e('Games Category')	  
			),
			get_user_meta( get_current_user_id(), 'category', true )
		  );

	}

	public function save_account_details( $user_id ) {
		
		update_user_meta( $user_id, 'platform', wc_clean( $_POST[ 'platform' ] ) );
        update_user_meta( $user_id, 'category', wc_clean( $_POST[ 'category' ] ) );
	
	}

 
    public function register_shortcodes(){
 
        add_shortcode( 'games_query_call','show_games_shortcode'  );
        
		function show_games_shortcode () {
       
			$current_user_id = get_current_user_id();
           
			$platform = get_user_meta( $current_user_id, 'platform', true );
			$category = get_user_meta( $current_user_id, 'category', true );

			$awesome_plugin_settings_options = get_option( 'awesome_plugin_settings_option_name' ); // Array of All Options
            $apikey = $awesome_plugin_settings_options['api_key_0']; // Api Key 
			//$apikey = '347f2b29e8mshd562d3f6589dcd4p1e5afdjsn2817b49a2bdc';
	
			if ($apikey != '' && $platform != '' && $category != '' ) {
             
			   $curl = curl_init();
		
		       curl_setopt_array($curl, [
			CURLOPT_URL => "https://free-to-play-games-database.p.rapidapi.com/api/games?platform=".$platform."&category=".$category,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_SSL_VERIFYHOST => false, //for testing in localhost, if set to false - security risk, never on production
			CURLOPT_SSL_VERIFYPEER => false, //for testing in localhost, if set to false - security risk, never on production
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => [
				"X-RapidAPI-Host: free-to-play-games-database.p.rapidapi.com",
				"X-RapidAPI-Key: ".$apikey
			],
		]);
		
		   $response = curl_exec($curl);
		   $err = curl_error($curl);
		
		   curl_close($curl);
		
		   if ($err) {
			 return "cURL Error #:" . $err;
		   } else {
		    
			$jsonObj = json_decode($response);
			$htmlResults = '';
			//TO DO : add pagination via shortcode attributes and probably a limit.
			foreach($jsonObj as $result) {
			   $htmlResults .= '<div class="game-div game-id-'.$result->id.'">';
			   $htmlResults .= '<h4>'.$result->title.'</h4>';
			   $htmlResults .= '<p>'.$result->short_description.'</p>';
			   $htmlResults .= '<a class="button" href="'.$result->game_url.'" target="_blank">View game Info</a>';
			   $htmlResults .= '</div>'; 	
			}

			return $htmlResults;
		}  

			
			}  else {
			  echo	'Please login and insert your choices to view your games';
			}
		  
		  }
    }
	
	

}
