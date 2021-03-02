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


?>

<?php 
require_once plugin_dir_path( dirname(__FILE__) ) .'partials/helpers/db.php';

$district_pre = "district_single_";
$key_term = "Add";
$district_id = "";
$district_result = "";
$district_input = array();
global $wpdb;
$table_name = $wpdb->prefix . "district_table"; 
$province_table = $wpdb->prefix . "province_table"; 
$table_name2 = $wpdb->prefix . "province_table_input"; 
if (isset($_GET['action'])){
    $key_term = "Edit";
    $district_id = $_GET['proid'];
    ?>
<div class="firebox-header">
    <div class="sing">
        <h2>Total Wards</h2>
        <h2><?php echo getWardsForDist($district_id); ?></h2>
    </div>
    <div class="sing">
        <h2>Total Municipalities</h2>
        <h2><?php echo getMuniForDist($district_id );?><span></span></h2>
    </div>
    <div class="sing">
        <h2>Metropolitan</h2>
        <h2><?php echo getMunTypeDist($district_id,"Metropolitan"); ?></h2>
    </div>
    <div class="sing">
        <h2>Sub Metropolis</h2>
        <h2><?php echo getMunTypeDist($district_id,"Sub-metropolis"); ?></h2>
    </div>
</div>
<div class="firebox-header">
    <div class="sing">
        <h2>Urban Municipalities</h2>
        <h2><?php echo getMunTypeDist($district_id,"Urban"); ?></h2>
    </div>
    <div class="sing">
        <h2>Rural Municipalities</h2>
        <h2><?php echo getMunTypeDist($district_id,"Rural"); ?></h2>
    </div>
    <div class="sing">
        <h2>Total Population</h2>
        <h2><?php echo getPopnDist($district_id );?></h2>
    </div>
    <div class="sing">
        <h2>Total Area</h2>
        <h2><?php echo getAreaDist($district_id );?><span> sq km</span></h2>
    </div>
</div>
<?php
}

if($district_id!=""){
    $district_result = $wpdb->get_results("SELECT * FROM $table_name WHERE id = $district_id");
}else{
    $district_result = $wpdb->get_results("SELECT * FROM $table_name");
}
$district_input = $wpdb->get_results("SELECT * FROM $table_name2");
$provinces = $wpdb->get_results("SELECT * FROM $province_table");

function get_type_from_name($name,$list){
    foreach($list as $l){
             if($l->name == $name){
                 return $l->types;
             }
    }
}
?>
<div class="fire-full-header">
    <h1><?php echo $key_term; ?> a district</h1>
</div>
<div class="fire-full content-form">
    <form method="post" action="">
        <?php 
        if($district_result != "" && $district_input != ""){
            foreach(get_object_vars($district_result[0]) as $key => $v){
                if($key != "id"){
                    $typ =  get_type_from_name($key,$district_input);
                ?>

        <p>
            <label for="<?php echo $district_pre .$key; ?>"><?php echo $key;?>:</label>
            <?php if($typ == "textarea"){ ?>
            <textarea type="text"
                name="<?php echo $district_pre .$key; ?>"><?php echo ($key_term == "Edit")? $v : '';?></textarea>
            <?php }else if($typ == "float") {?>
            <input type="number" step="any" name="<?php echo $district_pre .$key; ?>"
                value="<?php echo ($key_term == "Edit")?$v : '';?>" />
            <?php }else if($typ == "select"){ ?>
            <select name="<?php echo $district_pre .$key; ?>" id="proDrops">
                <?php if($key_term == "Edit"){
                        foreach($provinces as $va){ ?>
                <option data-id="<?php echo $va->id; ?>" value="<?php echo $va->name; ?>"
                    <?php echo ($v == $va->name) ? 'selected' : '';?>>
                    <?php echo $va->name; ?>
                </option>
                <?php }} else {
                        $i = 0;
                    foreach($provinces as $va){ ?>
                <option data-id="<?php echo $va->id; ?>" value="<?php echo $va->name; ?>"
                    <?php echo ($i == 0) ? 'selected' : '';?>><?php echo $va->name; ?>
                </option>
                <?php $i++; }} ?>
            </select>
            <?php } else { ?>
            <input type="<?php echo $typ; ?>" class="<?php echo ($key == "province_id")? 'fire-disable': '';?>"
                id="<?php echo $district_pre .$key; ?>" name="<?php echo $district_pre .$key; ?>"
                value="<?php echo ($key_term == "Edit")?$v : '';?>" />
            <?php } ?>
        </p>
        <?php
            }}
            ?>
        <hr>
        <input type="submit" value="<?php echo $key_term.' district'; ?>"
            name="<?php echo $district_pre .'submit'; ?>" />
        <?php
        }else{echo "Something is wrong";}?>

    </form>
</div>

<?php 
    //setting up the form
    function add_new_district($list,$type_list,$key_term) {
        $district_pre = "district_single_";
        $key_value = array();
        $per = array();
        $id = '';
        foreach(get_object_vars($list) as $key => $v){
            if($key == "id"){
                $id = $v;
            }
            else{
                $key_value[$key] = $_POST[$district_pre .$key];
                array_push($per,(get_type_from_name($key,$type_list) == "float") ? '%f' : '%s');
            }
        }
  
        global $wpdb; 
        $table_name = $wpdb->prefix . "district_table"; 
        if($key_term == "Add"):
            echo (!$wpdb->insert($table_name, $key_value ,$per))? 'Please dont duplicate name' : 'Sucessfully inserted';
        else:
            echo (!$wpdb->update($table_name, $key_value ,array('id'=>$id)))? 'Something is wrong' : 'Sucessfully updated';
        endif;
    }

  //And now to connect the  two:  
  if( isset($_POST[$district_pre .'submit']) ) add_new_district($district_result[0],$district_input,$key_term );

?>