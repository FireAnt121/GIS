<?php


// function createJson(){
//     global $wpdb;
//     $table = $wpdb->prefix . "province_table";
//     $res = $wpdb->get_results("SELECT * FROM $table");
//     $data_in = array();
//     $data = array(
//         "type" => "FeatureCollection",
//         "features" => $data_in
//     );

//     foreach($res as $r){
//         print_r($r);
//     }
// }


$plugin_dir = ABSPATH . 'wp-content/plugins/nepal-mapping/';
require_once $plugin_dir .'admin/partials/helpers/db.php';
$table_name = $_POST['table_name'];
		global $wpdb;

		$table = $wpdb->prefix . 'province_table';
		$res = $wpdb->get_results("SELECT * FROM $table");
	
		foreach($res as $r){
			$provinces[] = array(
				"name" => preg_replace('/\s/', '', $r->name),
				"id"   => $r->id
			);
			$datas[] = array(
				"type" => "Feature",
				"geometry" => array(
					"type" => "Polygon",
					"coordinates" => json_decode($r->coordinate)),
				"properties" => array(
					    "name"   => $r->name,
						"TARGET" => preg_replace('/\s/', '', $r->name),
						"lat"    => $r->latitude,
						"long"   => $r->longitude,
						"color"  => $r->color,
                        "nepali" => $r->nepali,
                        "T_D"    => getDisforProvince($r->id),
                        "T_M"    => getMuniforProvince($r->id),
                        "T_Metro"=> getMunTypeProv($r->id,"Metropolitan"),
                        "T_Sub"  => getMunTypeProv($r->id,"Sub-metropolis"),
                        "T_Urban"=> getMunTypeProv($r->id,"Urban"),
                        "T_Rural"=> getMunTypeProv($r->id,"Rural"),
                        "T_Pop"  => getPopnforProvince($r->id),
                        "T_Area" => getAreaforProvince($r->id)
				)
			);
		}
		$all_data[] =array(
		"type" => "FeatureCollection",
		"features" => $datas);

		$file = $plugin_dir. "admin/json/country.json";
		if(file_put_contents($file,json_encode($all_data,JSON_UNESCAPED_UNICODE))) 
			echo("created"); 
		else
            echo("failed"); 


			$table = $wpdb->prefix . 'district_table';
			foreach($provinces as $p){
			$res = $wpdb->get_results("SELECT * FROM $table WHERE province_id=$p[id]");
		    if($res != NULL):
				unset($datas);
				unset($all_data);
			foreach($res as $r){
				$districts[] = array(
					"name" => preg_replace('/\s/', '', $r->name),
					"id"   => $r->id
				);
				$datas[] = array(
					"type" => "Feature",
					"geometry" => array(
						"type" => "Polygon",
						"coordinates" => json_decode($r->coordinate)),
					"properties" => array(
							"name"   => $r->name,
							"TARGET" => preg_replace('/\s/', '', $r->name),
							"lat"    => $r->latitude,
							"long"   => $r->longitude,
							"color"  => $r->color,
                            "T_W"    => getWardsForDist($r->id),
                            "T_M"    => getMuniForDist($r->id),
                            "T_Metro"=> getMunTypeDist($r->id,"Metropolitan"),
                            "T_Sub"  => getMunTypeDist($r->id,"Sub-metropolis"),
                            "T_Urban"=> getMunTypeDist($r->id,"Urban"),
                            "T_Rural"=> getMunTypeDist($r->id,"Rural"),
                            "T_Pop"  => getPopnDist($r->id),
                            "T_Area" => getAreaDist($r->id)
					)
				);
			}
			$all_data[] =array(
			"type" => "FeatureCollection",
			"features" => $datas);
	
			$file = $plugin_dir . "admin/json/provinces/".preg_replace('/\s/', '', $p[name]).".json";
			if(file_put_contents($file,json_encode($all_data))) 
				echo("File created"); 
			else
				echo("Failed");
			endif;
			}

			$table = $wpdb->prefix . 'municipality_table';
			foreach($districts as $p){
			$res = $wpdb->get_results("SELECT * FROM $table WHERE district_id=$p[id]");
		    if($res != NULL):
				unset($datas);
				unset($all_data);
			foreach($res as $r){
				$tt = (substr($r->coordinate,0,4) == "[[[[") ? "MultiPolygon" : "Polygon";
				$datas[] = array(
					"type" => "Feature",
					"geometry" => array(
						"type" => $tt,
						"coordinates" => json_decode($r->coordinate)),
					"properties" => array(
							"ID"     => $r->id,
							"name"   => $r->name,
							"TARGET" => preg_replace('/\s/', '', $r->name),
							"lat"    => $r->latitude,
							"long"   => $r->longitude,
							"color"  => $r->color,
                            "T_W"    => getWardsForMuni($r->id),
                            "T_Pop"  => getPopnMuni($r->id),
                            "T_Area" => getAreaMuni($r->id)
					)
				);
			}
			$all_data[] =array(
			"type" => "FeatureCollection",
			"features" => $datas);
	
			$file = $plugin_dir . "admin/json/districts/".preg_replace('/\s/', '', $p[name]).".json";
			if(file_put_contents($file,json_encode($all_data))) 
				echo "created";
			else
				echo("Failed");
			endif;
			}

?>