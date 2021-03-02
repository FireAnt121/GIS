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

<h1>Province</h1>

<form method="POST" action="options.php">
    <?php
    settings_fields('wordpress-custom-plugin-options');
    do_settings_sections('wordpress-custom-plugin-options');
    submit_button();
  ?>
</form>