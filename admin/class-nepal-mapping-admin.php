<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       tenish.pb.design
 * @since      1.0.0
 *
 * @package    Nepal_Mapping
 * @subpackage Nepal_Mapping/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Nepal_Mapping
 * @subpackage Nepal_Mapping/admin
 * @author     FireAnt <prithakcreation@gmail.com>
 */

class Nepal_Mapping_Admin {

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
		add_action( 'admin_menu', array($this, 'addMenu') );
		add_action('wp_ajax_your_delete_action', array($this,'delete_row'));
		add_action( 'wp_ajax_nopriv_your_delete_action',  array($this, 'delete_row'));
		add_action('wp_ajax_your_json_action', array($this,'createJson'));
		add_action( 'wp_ajax_nopriv_your_json_action',  array($this, 'createJson'));
		add_shortcode( 'njengah_contact_form', array($this,'map_shortcode'));
		add_action('rest_api_init', function () {
			register_rest_route( 'w1/v1', 'prithak-nepals/(?P<id>\d+)',array(
						  'methods'  => 'GET',
						  'callback' => array($this,'get_wardss')
				));
		  });
	}

	function get_wardss($data){
		$plugin_dir = ABSPATH . 'wp-content/plugins/nepal-mapping/';
		require_once $plugin_dir .'admin/partials/helpers/db.php';
		return getWardsfromId(intval($data['id']));
		// require_once plugin_dir_path( dirname(__FILE__) ) . 'admin/partials/helpers/api.php';
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
		 * defined in Nepal_Mapping_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Nepal_Mapping_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/nepal-mapping-admin.css', array(), $this->version, 'all' );

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
		 * defined in Nepal_Mapping_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Nepal_Mapping_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/nepal-mapping-admin.js', array( 'jquery' ), $this->version, false );

	}
	function delete_row() {
		$id = explode('_', $_POST['element_id']);
		$table_name = $_POST['table_name'];
		if (wp_verify_nonce($id[2], $id[0] . '_' . $id[1])) {
			global $wpdb; 
			$table = $wpdb->prefix . $table_name; 
			$wpdb->delete( $table, array( 'id' => $id[1] ) );
	
			echo 'Deleted post';
			die;
		} else {
			echo 'Nonce not verified';
			die;
		}
	
	}
    
	function map_shortcode(){
		?>
<div id="mapid"></div>
<div id="mapid-content"></div>
<div id="mapinner-content">
	<div class="fire-close">
		<span></span>
		<span></span>
	</div>
	<div class="inner-content-box">

	</div>
</div>
<?php
	}

	function createJson(){
		require_once plugin_dir_path( dirname(__FILE__) ) . 'admin/partials/helpers/jsoncreateor.php';
	}
	
   /* Hook into the 'init' action so that the function
   * Containing our post type registration is not 
   * unnecessarily executed. 
   */

  public function addMenu() {
	  add_menu_page("Prithak Nepal", "Prithak Nepal", "edit_posts",
		  "prithaknepal", array($this,"displayPage"), null, 1);
		  add_submenu_page(
			'prithaknepal',
			'All Provinces',
			'All Provinces',
			'manage_options',
			'prithaknepal/provinces',
			array($this, 'displayPageProvince')
		);
		add_submenu_page(
			'prithaknepal',
			'Add Province',
			'Add Province',
			'manage_options',
			'prithaknepal/province/add',
			array($this,'addPageProvince')
		);
		//adding for district
		add_submenu_page(
			'prithaknepal',
			'All Districts',
			'All Districts',
			'manage_options',
			'prithaknepal/districts',
			array($this, 'displayPageDistrict')
		);
		add_submenu_page(
			'prithaknepal',
			'Add District',
			'Add District',
			'manage_options',
			'prithaknepal/district/add',
			array($this,'addPageDistrict')
		);
		//adding for municipality
		add_submenu_page(
			'prithaknepal',
			'All Municiplaity',
			'All Municipality',
			'manage_options',
			'prithaknepal/municipalities',
			array($this, 'displayPageMunicipality')
		);
		add_submenu_page(
			'prithaknepal',
			'Add Municiplaity',
			'Add Municipality',
			'manage_options',
			'prithaknepal/municipality/add',
			array($this,'addPageMunicipality')
		);
				//adding for ward
				add_submenu_page(
					'prithaknepal',
					'All Wards',
					'All Wards',
					'manage_options',
					'prithaknepal/wards',
					array($this, 'displayPageWard')
				);
				add_submenu_page(
					'prithaknepal',
					'Add Ward',
					'Add Ward',
					'manage_options',
					'prithaknepal/ward/add',
					array($this,'addPageWard')
				);
  }

  public function displayPage(){
		require_once plugin_dir_path( dirname(__FILE__) ) . 'admin/partials/display_top_menu.php';
  }

  public function displayPageProvince(){
	  require_once plugin_dir_path( dirname(__FILE__) ) . 'admin/partials/display_province.php';
  }

  public function addPageProvince(){
	  require_once plugin_dir_path( dirname(__FILE__) ) .'admin/partials/add_province.php';
  }

  public function displayPageDistrict(){
	require_once plugin_dir_path( dirname(__FILE__) ) . 'admin/partials/display_district.php';
	}

	public function addPageDistrict(){
		require_once plugin_dir_path( dirname(__FILE__) ) .'admin/partials/add_district.php';
	}

	public function displayPageMunicipality(){
		require_once plugin_dir_path( dirname(__FILE__) ) . 'admin/partials/display_municipality.php';
	}
	
	public function addPageMunicipality(){
		require_once plugin_dir_path( dirname(__FILE__) ) .'admin/partials/add_municipality.php';
	}

	public function displayPageWard(){
		require_once plugin_dir_path( dirname(__FILE__) ) . 'admin/partials/display_ward.php';
	}
	
	public function addPageWard(){
		require_once plugin_dir_path( dirname(__FILE__) ) .'admin/partials/add_ward.php';
	}


}