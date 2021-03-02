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

$municipality_pre = "municipality_single_";
$key_term = "Add";
$municipality_id = "";
$municipality_result = "";
$municipality_input = array();
global $wpdb;
$table_name = $wpdb->prefix . "municipality_table"; 
$district_table = $wpdb->prefix . "district_table"; 
$table_name2 = $wpdb->prefix . "province_table_input"; 
if (isset($_GET['action'])){
    $key_term = "Edit";
    $municipality_id = $_GET['proid'];
    ?>
<div class="fire-wrapper">
    <div class="firebox-header">
        <div class="sing">
            <h2>Total Wards</h2>
            <h2><?php print_r(getWardsForMuni($municipality_id)); ?></h2>
        </div>
        <div class="sing">
            <h2>Total Population</h2>
            <h2><?php echo getPopnMuni($municipality_id );?></h2>
        </div>
        <div class="sing">
            <h2>Total Area</h2>
            <h2><?php echo getAreaMuni($municipality_id );?><span> sq km</span></h2>
        </div>
    </div>
    <?php
}

if($municipality_id!=""){
    $municipality_result = $wpdb->get_results("SELECT * FROM $table_name WHERE id = $municipality_id");
}else{
    $municipality_result = $wpdb->get_results("SELECT * FROM $table_name");
}
$municipality_input = $wpdb->get_results("SELECT * FROM $table_name2");
$districts = $wpdb->get_results("SELECT * FROM $district_table");

function get_type_from_name($name,$list){
    foreach($list as $l){
             if($l->name == $name){
                 return $l->types;
             }
    }
}
?>
    <div class="fire-full-header">
        <h1><?php echo $key_term; ?> a municipality</h1>
    </div>
    <div class="fire-full content-form">
        <form method="post" action="">
            <select id="provdropinMuni">
                <?php
                    foreach(getProvinces() as $pro){
                        ?>
                <option data-id="<?php echo $pro->id;?>"><?php echo $pro->name;?></option>
                <?php
                    }
                ?>
            </select>
            <?php 
        if($municipality_result != "" && $municipality_input != ""){
            foreach(get_object_vars($municipality_result[0]) as $key => $v){
                if($key != "id"){
                    $typ =  get_type_from_name($key,$municipality_input);
                ?>

            <p>
                <label for="<?php echo $municipality_pre .$key; ?>"><?php echo $key;?>:</label>
                <?php if($typ == "textarea"){ ?>
                <textarea type="text"
                    name="<?php echo $municipality_pre .$key; ?>"><?php echo ($key_term == "Edit")? $v : '';?></textarea>
                <?php }else if($typ == "float") {?>
                <input type="number" step="any" name="<?php echo $municipality_pre .$key; ?>"
                    value="<?php echo ($key_term == "Edit")?$v : '';?>" />
                <?php }else if($typ == "select"){
                    if($key == "type") {?>
                <select name="<?php echo $municipality_pre .$key; ?>">
                    <option value="Metropolitan"
                        <?php echo ($key_term == "Edit" && $v == 'Metropolitan') ? 'selected' : '';?>>
                        Metropolitan
                    </option>
                    <option value="Sub-metropolis"
                        <?php echo ($key_term == "Edit" && $v == 'Sub-metropolis') ? 'selected' : '';?>>
                        Sub-metropolis
                    </option>
                    <option value="Urban" <?php echo ($key_term == "Edit" && $v == 'Urban') ? 'selected' : '';?>>
                        Urban
                    </option>
                    <option value="Rural" <?php echo ($key_term == "Edit" && $v == 'Rural') ? 'selected' : '';?>>
                        Rural
                    </option>
                    <option value="National-park"
                        <?php echo ($key_term == "Edit" && $v == 'National-park') ? 'selected' : '';?>>
                        National Park
                    </option>
                    <option value="Wildlife-reserve"
                        <?php echo ($key_term == "Edit" && $v == 'Wildlife-reserve') ? 'selected' : '';?>>
                        Wildlife Reserve
                    </option>
                </select>
                <?php }else { ?>
                <select name="<?php echo $municipality_pre .$key; ?>" id="muniDrops">
                    <?php if($key_term == "Edit"){
                        foreach($districts as $va){ ?>
                    <option data-id="<?php echo $va->id; ?>" pro-id="<?php echo $va->province_id; ?>"
                        value="<?php echo $va->name; ?>" <?php echo ($v == $va->name) ? 'selected' : '';?>>
                        <?php echo $va->name; ?>
                    </option>
                    <?php }} else {
                        $i = 0;
                    foreach($districts as $va){ ?>
                    <option data-id="<?php echo $va->id; ?>" pro-id="<?php echo $va->province_id; ?>"
                        value="<?php echo $va->name; ?>" <?php echo ($i == 0) ? 'selected' : '';?>>
                        <?php echo $va->name; ?>
                    </option>
                    <?php $i++; }} ?>
                </select>
                <?php }} else { ?>
                <input type="<?php echo $typ; ?>" class="<?php echo ($key == "district_id")? 'fire-disable': '';?>"
                    name="<?php echo $municipality_pre .$key; ?>" id="<?php echo $municipality_pre .$key; ?>"
                    value="<?php echo ($key_term == "Edit")?$v : '';?>" />
                <?php } ?>
            </p>
            <?php
            }}
            ?>
            <hr>
            <input type="submit" value="Submit" name="<?php echo $municipality_pre .'submit'; ?>" />
            <?php
        }else{echo "Something is wrong";}?>

        </form>
    </div>
</div>
<?php 
    //setting up the form
    function add_new_municipality($list,$type_list,$key_term) {
        $municipality_pre = "municipality_single_";
        $key_value = array();
        $per = array();
        $id = '';
        foreach(get_object_vars($list) as $key => $v){
            if($key == "id"){
                $id = $v;
            }
            else{
                $key_value[$key] = $_POST[$municipality_pre .$key];
                array_push($per,(get_type_from_name($key,$type_list) == "float") ? '%f' : '%s');
            }
        }
  
        global $wpdb; 
        $table_name = $wpdb->prefix . "municipality_table"; 
        if($key_term == "Add"):
            echo (!$wpdb->insert($table_name, $key_value ,$per))? 'Please dont duplicate name' : 'Sucessfully inserted';
        else:
            echo (!$wpdb->update($table_name, $key_value ,array('id'=>$id)))? 'Please dont duplicate name' : 'Sucessfully updated';
        endif;
    }

  //And now to connect the  two:  
  if( isset($_POST[$municipality_pre .'submit']) ) add_new_municipality($municipality_result[0],$municipality_input,$key_term );

?>