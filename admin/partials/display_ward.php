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
<div class="fire-full-header">
    <h1>All Wards</h1>
    <a class="add-button" href="<?php echo  admin_url('admin.php?page=prithaknepal/ward/add'); ?>">Add New Ward</a>
</div>


<?php 
$ward_pre = "ward_single_";
global $wpdb;
$table_name = $wpdb->prefix . "ward_table"; 
$post_id = $wpdb->get_results("SELECT * FROM $table_name");

?>

<div class="fire-full">
    <?php if($post_id != NULL): ?>
    <table>
        <thead>
            <tr>
                <?php foreach(get_object_vars($post_id[0]) as $key => $v){?>
                <th> <?php echo ($key == "name") ? "Former VDC/Municipality" : $key; ?> </th>
                <?php } ?>
                <th> Options </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($post_id as $p){?>
            <tr>
                <?php foreach(get_object_vars($p) as $key=>$v){?>
                <td><?php 
                    echo $v; ?></td>
                <?php } ?>
                <td>
                    <a class="edit"
                        href="<?php echo admin_url('admin.php?page=prithaknepal/ward/add&proid='.$p->id.'&action=edit')?>">Edit</a>
                    <button class="delete" table="ward_table"
                        id="<?php echo 'delete_'.$p->id.'_'.wp_create_nonce('delete_' . $p->id ); ?>">Delete</button>
                </td>
            </tr>
            <?php }?>
        </tbody>
    </table>
    <?php else: ?>
    <h3>No wards Found</h3>
    <?php endif; ?>
</div>