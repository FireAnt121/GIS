<?php

/**
 * Fired during plugin activation
 *
 * @link       tenish.pb.design
 * @since      1.0.0
 *
 * @package    Nepal_Mapping
 * @subpackage Nepal_Mapping/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Nepal_Mapping
 * @subpackage Nepal_Mapping/includes
 * @author     FireAnt <prithakcreation@gmail.com>
 */
class Nepal_Mapping_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;
		$table_name = $wpdb->prefix .'province_table';
		$table_name2 = $wpdb->prefix .'province_table_input';

		$table_dis = $wpdb->prefix .'district_table';
		$table_municipality = $wpdb->prefix .'municipality_table';
		$table_ward = $wpdb->prefix .'ward_table';
		$charset_collate = $wpdb->get_charset_collate();
		
		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		  id mediumint(9) NOT NULL AUTO_INCREMENT,
		  name varchar(30) NOT NULL UNIQUE,
		  nepali tinytext NOT NULL,
		  coordinate longtext NOT NULL,
		  latitude float NOT NULL,
		  longitude float NOT NULL,
		  color varchar(255) NOT NULL,
		  PRIMARY KEY  (id)
		) $charset_collate;";
		
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		$sql2 = "CREATE TABLE IF NOT EXISTS $table_name2 (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			name varchar(30) NOT NULL UNIQUE,
			types varchar(255) NOT NULL,
			PRIMARY KEY  (id)
		  ) $charset_collate;";
		  
		  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		//   dbDelta( $sql2 );
		$wpdb->query($sql2);

		$sql = "CREATE TABLE IF NOT EXISTS $table_dis (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			name varchar(50) NOT NULL,
			nepali tinytext NOT NULL,
			coordinate longtext NOT NULL,
			province varchar(50) NOT NULL,
			province_id mediumint(9) NOT NULL,
			PRIMARY KEY  (id),
			FOREIGN KEY (province_id) REFERENCES $table_name(id) 
		  ) $charset_collate;";
		  
		  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		  dbDelta( $sql );


		  $sql = "CREATE TABLE IF NOT EXISTS $table_municipality (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			name varchar(50) NOT NULL,
			nepali tinytext NOT NULL,
			type tinytext NOT NULL,
			coordinate longtext NOT NULL,
			district varchar(50) NOT NULL,
			district_id mediumint(9) NOT NULL,
			PRIMARY KEY  (id),
			FOREIGN KEY (district_id) REFERENCES $table_dis(id)
		  ) $charset_collate;";
		  
		  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		  dbDelta( $sql );

		  $sql = "CREATE TABLE IF NOT EXISTS $table_ward (
			id int(50) NOT NULL AUTO_INCREMENT,
			No int(50) NOT NULL,
			name tinytext NOT NULL,
			nepali tinytext NOT NULL,
			population int(100) NOT NULL,
			area float NOT NULL,
			municipality varchar(50) NOT NULL,
			municipality_id mediumint(9) NOT NULL,
			PRIMARY KEY  (id),
			FOREIGN KEY (municipality_id) REFERENCES $table_municipality(id)
		  ) $charset_collate;";
		  
		  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		  dbDelta( $sql );
	}

}