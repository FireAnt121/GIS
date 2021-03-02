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
<div class="wrap">
    <h1>Prithak Nepal</h1>

    <form method="post" action="">

        <h3>Add column in Province Table</h3>
        <p>
            <label for="column_name">Name:</label>
            <input type="text" name="column_name" />
        </p>
        <p>
            <label for="column_type">Name:</label>
            <select name="column_type">
                <option value="text">text</option>
                <option value="varchar(255)">varchar(255)</option>
                <option value="tinytext">tinytext</option>
                <option value="longtext">longtext</option>
                <option value="int(11)">int(11)</option>
            </select>
        </p>
        <p>
            <label for="column_input_type">Type:</label>
            <select name="column_input_type">
                <option value="text">text</option>
                <option value="number">number</option>
                <option value="color">color</option>
                <option value="textarea">textarea</option>
                <option value="float">decimal number</option>
            </select>
        </p>
        <hr>
        <input type="submit" value="Submit" name="province_column_submit" />
    </form>
</div>

<?php 
    function alerting_province_table(){
        $c_name = $_POST['column_name'];
        $c_type = $_POST['column_type'];
        $c_input = $_POST['column_input_type'];
        global $wpdb; 
        $table_name = $wpdb->prefix . "province_table"; 
        $table_name2 = $wpdb->prefix . "province_table_input"; 
        $row = $wpdb->get_results( "SELECT * FROM $table_name");
            if(!isset($row->$c_name)){
            $wpdb->query("ALTER TABLE $table_name ADD $c_name $c_type NOT NULL");
            }


            $wpdb->insert($table_name2, array(
                'name' => $c_name, 
                'types' => $c_input,
                ),array(
                '%s',
                '%s') 
            );
    }

    if( isset($_POST['province_column_submit']) ) alerting_province_table();
?>