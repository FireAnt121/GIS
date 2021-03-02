<?php

function getWardsfromId($id){
    global $wpdb;
    $table= $wpdb->prefix . "ward_table";
    return $wpdb->get_results("SELECT * FROM $table WHERE municipality_id=$id");
}
function getWardsForMuni($muni_id){
        global $wpdb;
        $table = $wpdb->prefix . "ward_table";
        $result = $wpdb->get_var("SELECT COUNT(id) FROM $table WHERE municipality_id=$muni_id");
        return $result;
    }
    function getPopnMuni($muni_id){
    global $wpdb;
    $table = $wpdb->prefix . "ward_table";
    $result = $wpdb->get_var("SELECT SUM(population) FROM $table WHERE municipality_id=$muni_id");
    // $popn = 0;
    //     foreach($result as $r){
    //         $popn += $r->population;
    //     }
    return $result;
    }

    function getAreaMuni($muni_id){
        global $wpdb;
        $table = $wpdb->prefix . "ward_table";
        $result = $wpdb->get_results("SELECT area FROM $table WHERE municipality_id=$muni_id");
        $area = 0;
            foreach($result as $r){
                $area += $r->area;
            }
        return $area;
    }

    function getProvinces(){
        global $wpdb;
        $p = $wpdb->prefix . "province_table";
        return $wpdb->get_results(" SELECT * from $p");
    }
    function getDistricts(){
        global $wpdb;
        $p = $wpdb->prefix . "district_table";
        return $wpdb->get_results(" SELECT * from $p ORDER BY name ASC");
    }
    //for district

    function getWardsForDist($dist_id){
        global $wpdb;
        $table = $wpdb->prefix . "district_table";
        $table1 = $wpdb->prefix . "municipality_table";
        $table2 = $wpdb->prefix . "ward_table";
        $result = $wpdb->get_var("SELECT COUNT($table2.id) FROM (($table INNER JOIN $table1 ON $table.id = $table1.district_id) INNER JOIN $table2 ON $table1.id = $table2.municipality_id) WHERE $table.id = $dist_id ");
        return $result;
    }
    function getMuniForDist($dist_id){
        global $wpdb;
        $table = $wpdb->prefix . "municipality_table";
        $result = $wpdb->get_var("SELECT COUNT(id) FROM $table WHERE district_id=$dist_id");
        return $result;
    }
    function getPopnDist($dist_id){
    global $wpdb;
    $table = $wpdb->prefix . "district_table";
    $table1 = $wpdb->prefix . "municipality_table";
    $table2 = $wpdb->prefix . "ward_table";
    $result = $wpdb->get_var("SELECT SUM($table2.population) FROM (($table INNER JOIN $table1 ON $table.id = $table1.district_id) INNER JOIN $table2 ON $table1.id = $table2.municipality_id) WHERE $table.id = $dist_id ");
    // $popn = 0;
    //     foreach($result as $r){
    //         $popn += $r->population;
    //     }
    return $result??0;
    }

    function getMunTypeDist($dist_id,$typ){
        global $wpdb;
        $table = $wpdb->prefix . "district_table";
        $table1 = $wpdb->prefix . "municipality_table";
        $result = $wpdb->get_var("SELECT COUNT($table1.type) FROM ($table INNER JOIN $table1 ON $table.id = $table1.district_id) WHERE $table.id = $dist_id AND $table1.type = '$typ'");
        return $result??0;
    }

    function getAreaDist($dist_id){
        global $wpdb;
        $table = $wpdb->prefix . "district_table";
        $table1 = $wpdb->prefix . "municipality_table";
        $table2 = $wpdb->prefix . "ward_table";
        $result = $wpdb->get_var("SELECT SUM($table2.area) FROM (($table INNER JOIN $table1 ON $table.id = $table1.district_id) INNER JOIN $table2 ON $table1.id = $table2.municipality_id) WHERE $table.id = $dist_id ");
        // $area = 0;
        //     foreach($result as $r){
        //         $area += $r->area;
        //     }
        return number_format($result??0,2) ;
    }

    //for province

    function getDisforProvince($prov_id){
        global $wpdb;
        $table = $wpdb->prefix . "district_table";
        $result = $wpdb->get_var("SELECT COUNT(id) FROM $table WHERE province_id=$prov_id");
        return $result;
    }
    function getMuniforProvince($prov_id){
        global $wpdb;
        $table = $wpdb->prefix . "province_table";
        $table1 = $wpdb->prefix . "district_table";
        $table2 = $wpdb->prefix . "municipality_table";
        $result = $wpdb->get_var("SELECT COUNT($table2.id) FROM (($table INNER JOIN $table1 ON $table.id=$table1.province_id) INNER JOIN $table2 ON $table1.id=$table2.district_id ) WHERE $table1.province_id = $prov_id AND ( $table2.type = 'Rural' OR $table2.type ='Urban' OR $table2.type = 'Metropolitan' OR $table2.type = 'Sub-metropolis')");
        return $result;
    }
    function getPopnforProvince($prov_id){
        global $wpdb;
        $p = $wpdb->prefix . "province_table";
        $table = $wpdb->prefix . "district_table";
        $table1 = $wpdb->prefix . "municipality_table";
        $table2 = $wpdb->prefix . "ward_table";
        $result = $wpdb->get_results("SELECT $table2.population FROM ((($p INNER JOIN $table ON $p.id=$table.province_id) INNER JOIN $table1 ON $table.id=$table1.district_id) INNER JOIN $table2 ON $table1.id=$table2.municipality_id) WHERE $table.province_id = $prov_id");
        $popn = 0;
            foreach($result as $r){
                $popn += $r->population;
            }
        return $popn;
    }

    function getAreaforProvince($prov_id){
        global $wpdb;
        $p = $wpdb->prefix . "province_table";
        $table = $wpdb->prefix . "district_table";
        $table1 = $wpdb->prefix . "municipality_table";
        $table2 = $wpdb->prefix . "ward_table";
        $result = $wpdb->get_results("SELECT $table2.area FROM ((($p INNER JOIN $table ON $p.id=$table.province_id) INNER JOIN $table1 ON $table.id=$table1.district_id) INNER JOIN $table2 ON $table1.id=$table2.municipality_id) WHERE $table.province_id = $prov_id");
        $popn = 0;
            foreach($result as $r){
                $popn += $r->area;
            }
        return $popn;
    }

    function getMunTypeProv($prov_id,$typ){
        global $wpdb;
        $p = $wpdb->prefix . "province_table";
        $table = $wpdb->prefix . "district_table";
        $table1 = $wpdb->prefix . "municipality_table";
        $result = $wpdb->get_var("SELECT COUNT($table1.type) FROM (($p INNER JOIN $table ON $p.id = $table.province_id) INNER JOIN $table1 ON $table.id = $table1.district_id) WHERE $table.province_id = $prov_id AND $table1.type = '$typ'");
        return $result??0;
    }
?>