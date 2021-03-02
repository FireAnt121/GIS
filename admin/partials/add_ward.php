<?php 

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       tenish.pb.design
 * @since      1.0.0
 *
 * @package    Nepal_Mapping
 * @subpackage Nepal_Mapping/admin/partials
 */

require_once plugin_dir_path( dirname(__FILE__) ) .'partials/helpers/db.php';

?>

<?php 
$ward_pre = "ward_single_";
$key_term = "Add";
$ward_id = "";
$ward_result = "";
$ward_input = array();
global $wpdb;
$table_name = $wpdb->prefix . "ward_table"; 
$municipality_table = $wpdb->prefix . "municipality_table"; 
$table_name2 = $wpdb->prefix . "province_table_input"; 
if (isset($_GET['action'])){
    $key_term = "Edit";
    $ward_id = $_GET['proid'];
}

if($ward_id!=""){
    $ward_result = $wpdb->get_results("SELECT * FROM $table_name WHERE id = $ward_id");
}else{
    $ward_result = $wpdb->get_results("SELECT * FROM $table_name");
}
$ward_input = $wpdb->get_results("SELECT * FROM $table_name2");
$municipalitys = $wpdb->get_results("SELECT * FROM $municipality_table");

function get_type_from_name($name,$list){
    foreach($list as $l){
             if($l->name == $name){
                 return $l->types;
             }
    }
}
?>
<div class="fire-full content-form">
    <form method="post" action="">
        <h3><?php echo $key_term; ?> a ward</h3>
        <select id="provdropinMuni">
            <?php
                    foreach(getProvinces() as $pro){
                        ?>
            <option data-id="<?php echo $pro->id;?>"><?php echo $pro->name;?></option>
            <?php
                    }
                ?>
        </select>
        <select id="muniDrops">
            <?php
                    foreach(getDistricts() as $pro){
                        ?>
            <option data-id="<?php echo $pro->id;?>" pro-id="<?php echo $pro->province_id?>"><?php echo $pro->name;?>
            </option>
            <?php
                    }
                ?>
        </select>
        <?php 
        if($ward_result != "" && $ward_input != ""){
            foreach(get_object_vars($ward_result[0]) as $key => $v){
                if($key != "id"){
                    $typ =  get_type_from_name($key,$ward_input);
                ?>

        <p>
            <?php $lab = ($key == "name") ? "Former VDC/Municipality" : $key; ?>
            <label for="<?php echo $ward_pre .$key; ?>"><?php echo $lab;?>:</label>
            <?php if($typ == "textarea"){ ?>
            <textarea type="text"
                name="<?php echo $ward_pre .$key; ?>"><?php echo ($key_term == "Edit")? $v : '';?></textarea>
            <?php }else if($typ == "float") {?>
            <input type="number" step="any" name="<?php echo $ward_pre .$key; ?>"
                value="<?php echo ($key_term == "Edit")?$v : '';?>" />
            <?php }else if($typ == "select"){ ?>
            <select name="<?php echo $ward_pre .$key; ?>" id="wardDrops">
                <?php if($key_term == "Edit"){foreach($municipalitys as $va){ ?>
                <option data-id="<?php echo $va->id; ?>" dis-id="<?php echo $va->district_id; ?>"
                    value="<?php echo $va->name; ?>" <?php echo ($v == $va->name) ? 'selected' : '';?>>
                    <?php echo $va->name; ?>
                </option>
                <?php }}else{$i = 0;
                    foreach($municipalitys as $va){ ?>
                <option data-id="<?php echo $va->id; ?>" dis-id="<?php echo $va->district_id; ?>"
                    value="<?php echo $va->name; ?>" <?php echo ($i == 0) ? 'selected' : '';?>><?php echo $va->name; ?>
                </option>
                <?php $i++;}} ?>
            </select>
            <?php } else { ?>
            <input type="<?php echo $typ; ?>" class="<?php echo ($key == "municipality_id")? 'fire-disable': '';?>"
                name="<?php echo $ward_pre .$key; ?>" id="<?php echo $ward_pre .$key; ?>"
                value="<?php echo ($key_term == "Edit")?$v : '';?>" />
            <?php } ?>
        </p>
        <?php
            }}
            ?>
        <hr>
        <input type="submit" value="Submit" name="<?php echo $ward_pre .'submit'; ?>" />
        <?php
        }else{echo "Something is wrong";}?>

    </form>
</div>

<?php 
    //setting up the form
    function add_new_ward($list,$type_list,$key_term) {
        $ward_pre = "ward_single_";
        $key_value = array();
        $per = array();
        $id = '';
        foreach(get_object_vars($list) as $key => $v){
            if($key == "id"){
                $id = $v;
            }
            else{
                $key_value[$key] = $_POST[$ward_pre .$key];
                array_push($per,(get_type_from_name($key,$type_list) == "float") ? '%f' : '%s');
            }
        }
  
        global $wpdb; 
        $table_name = $wpdb->prefix . "ward_table"; 
        if($key_term == "Add"):
            echo (!$wpdb->insert($table_name, $key_value ,$per))? 'Please dont duplicate name' : 'Sucessfully inserted';
        else:
            echo (!$wpdb->update($table_name, $key_value ,array('id'=>$id)))? 'Please dont duplicate name' : 'Sucessfully updated';
        endif;
    }

  //And now to connect the  two:  
  if( isset($_POST[$ward_pre .'submit']) ) add_new_ward($ward_result[0],$ward_input,$key_term );

?>