<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       tenish.pb.design
 * @since      1.0.0
 *
 * @package    Nepal_Mapping
 * @subpackage Nepal_Mapping/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Nepal_Mapping
 * @subpackage Nepal_Mapping/public
 * @author     FireAnt <prithakcreation@gmail.com>
 */
class Nepal_Mapping_Public {

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
		add_filter( 'style_loader_tag', array($this,'add_leaflet_attributes'), 10, 2 );
		add_action('rest_api_init', function () {
			register_rest_route( 'w1/v1', 'prithak-nepal',array(
						  'methods'  => 'GET',
						  'callback' => array($this,'get_wards')
				));
		  });
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
		 * defined in Nepal_Mapping_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Nepal_Mapping_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/nepal-mapping-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( "leafletcss","https://unpkg.com/leaflet@1.7.1/dist/leaflet.css", array(),null );

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
		 * defined in Nepal_Mapping_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Nepal_Mapping_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/nepal-mapping-public.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( "leafletjs","https://unpkg.com/leaflet@1.7.1/dist/leaflet.js", array('jquery'),null );
		wp_enqueue_script( "customjs",plugin_dir_url( __FILE__ ) . "js/custom.js", array('jquery'),null,true);
		wp_localize_script('customjs', 'myScript', array(
			'pluginsUrl' => plugins_url(),
		));
	}
	function add_leaflet_attributes( $html, $handle ) {
		if ( 'leafletcss' === $handle ) {
			return str_replace( "media='all'", "media='all' integrity='sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=='	crossorigin=''", $html );
		}
		return $html;
	}

	function add_leaflet_js_attributes( $html, $handle ) {
		if ( 'leafletjs' === $handle ) {
			return str_replace( "media='all'", "media='all' integrity='sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=='	crossorigin=''", $html );
		}
		return $html;
	}

	function get_wards(){
		return "yo";
	}
}