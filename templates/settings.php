<?php mainlib_magellan_acf_admin_enqueue_scripts(); ?>
<div>
  <h2>Magellan Settings</h2>
  <form method="post" action="options.php">
    <?php settings_fields( 'mainlib_magellan_options_group' ); ?>
    <table>
      <tr valign="top">
        <th scope="row"><label for="mainlib_magellan_locations_path">Path to Locations page</label></th>
        <td><input type="text" id="mainlib_magellan_locations_path" name="mainlib_magellan_locations_path" value="<?php echo get_option('mainlib_magellan_locations_path'); ?>" /></td>
      </tr>
        <tr valign="top">
            <th scope="row"><label for="mainlib_magellan_default_icon">Default Library Service Icon</label></th>
            <td><input type="text" id="mainlib_magellan_default_icon" name="mainlib_magellan_default_icon" class="magellan-fa-select" value="<?php echo get_option('mainlib_magellan_default_icon'); ?>" /></td>
        </tr>
    </table>
    <?php  submit_button(); ?>
  </form>
</div>
