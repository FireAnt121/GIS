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

$province_pre = "province_single_";
$key_term = "Add";
$province_id = "";
$province_result = "";
$province_input = array();
global $wpdb;
$table_name = $wpdb->prefix . "province_table"; 
$table_name2 = $wpdb->prefix . "province_table_input"; 
if (isset($_GET['action'])){
    $key_term = "Edit";
    $province_id = $_GET['proid'];
    ?>
<div class="firebox-header">
    <div class="sing">
        <h2>Total Districts</h2>
        <h2><?php echo getDisforProvince($province_id); ?></h2>
    </div>
    <div class="sing">
        <h2>Total Municipalities</h2>
        <h2><?php echo  getMuniforProvince($province_id); ?></h2>
    </div>
    <div class="sing">
        <h2>Metropolitan</h2>
        <h2><?php echo getMunTypeProv($province_id,"Metropolitan"); ?></h2>
    </div>
    <div class="sing">
        <h2>Sub Metropolis</h2>
        <h2><?php echo getMunTypeProv($province_id,"Sub-metropolis"); ?></h2>
    </div>
</div>
<div class="firebox-header">
    <div class="sing">
        <h2>Urban Municipalities</h2>
        <h2><?php echo getMunTypeProv($province_id,"Urban"); ?></h2>
    </div>
    <div class="sing">
        <h2>Rural Municipalities</h2>
        <h2><?php echo getMunTypeProv($province_id,"Rural"); ?></h2>
    </div>
    <div class="sing">
        <h2>Total Population</h2>
        <h2><?php echo getPopnforProvince($province_id);?></h2>
    </div>
    <div class="sing">
        <h2>Total Area</h2>
        <h2><?php echo getAreaforProvince($province_id);?><span> sq km</span></h2>
    </div>
</div>
<?php
}

if($province_id!=""){
    $province_result = $wpdb->get_results("SELECT * FROM $table_name WHERE id = $province_id");
}else{
    $province_result = $wpdb->get_results("SELECT * FROM $table_name");
}
$province_input = $wpdb->get_results("SELECT * FROM $table_name2");


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
        <h3><?php echo $key_term; ?> a Province</h3>
        <?php 
        if($province_result != "" && $province_input != ""){
            foreach(get_object_vars($province_result[0]) as $key => $v){
                if($key != "id"){
                    $typ =  get_type_from_name($key,$province_input);
                ?>

        <p>
            <label for="<?php echo $province_pre .$key; ?>"><?php echo $key;?>:</label>
            <?php if($typ == "textarea"){ ?>
            <textarea type="text"
                name="<?php echo $province_pre .$key; ?>"><?php echo ($key_term == "Edit")? $v : '';?></textarea>
            <?php }else if($typ == "float") {?>
            <input type="number" step="any" name="<?php echo $province_pre .$key; ?>"
                value="<?php echo ($key_term == "Edit")?$v : '';?>" />
            <?php }else { ?>
            <input type="<?php echo $typ; ?>" name="<?php echo $province_pre .$key; ?>"
                value="<?php echo ($key_term == "Edit")?$v : '';?>" />
            <?php } ?>
        </p>
        <?php
            }}
            ?>
        <hr>
        <input type="submit" value="Submit" name="<?php echo $province_pre .'submit'; ?>" />
        <?php
        }else{echo "Something is wrong";}?>

    </form>
</div>

<?php 
    //setting up the form
    function add_new_province($list,$type_list,$key_term) {
        $province_pre = "province_single_";
        $key_value = array();
        $per = array();
        $id = '';
        foreach(get_object_vars($list) as $key => $v){
            if($key == "id"){
                $id = $v;
            }
            else{
                $key_value[$key] = $_POST[$province_pre .$key];
                array_push($per,(get_type_from_name($key,$type_list) == "float") ? '%f' : '%s');
            }
        }
  
        global $wpdb; 
        $table_name = $wpdb->prefix . "province_table"; 
        if($key_term == "Add"):
            echo (!$wpdb->insert($table_name, $key_value ,$per))? 'Please dont duplicate name' : 'Sucessfully inserted';
        else:
            echo (!$wpdb->update($table_name, $key_value ,array('id'=>$id)))? 'Please dont duplicate name' : 'Sucessfully updated';
        endif;
    }

  //And now to connect the  two:  
  if( isset($_POST[$province_pre .'submit']) ) add_new_province($province_result[0],$province_input,$key_term );

?>