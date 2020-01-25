<?php mainlib_magellan_acf_admin_enqueue_scripts(); ?>
<form method="post" action="options.php">
    <div class="postbox-container">
        <div class="metabox-holder">
            <div class="postbox">
                <h3 class="hndle"><span>Magellan Settings</span></h3>
                <div class="inside">
                  <?php settings_fields( 'mainlib_magellan_options_group' ); ?>
                    <p>
                        <label for="mainlib_magellan_locations_path"><strong>Path to Locations Page:</strong></label>
                    </p>
                    <p>
                        <span><?php echo get_site_url(); ?>/</span><input type="text" id="mainlib_magellan_locations_path" name="mainlib_magellan_locations_path" value="<?php echo get_option('mainlib_magellan_locations_path'); ?>" /><span>/</span>
                    </p>
                    <hr>
                    <p>
                        <label for="mainlib_magellan_default_icon"><strong>Default Library Service Icon:</strong></label>
                    </p>
                    <p>
                        <input type="text" id="mainlib_magellan_default_icon" name="mainlib_magellan_default_icon" class="magellan-fa-select" value="<?php echo get_option('mainlib_magellan_default_icon'); ?>" />
                    </p>
                  <?php  submit_button(); ?>
                </div>
            </div>
        </div>
    </div>
</form>
<div style="clear:both;">
<form method="post" action="<?php echo admin_url( 'admin.php' ); ?>">
<input type="hidden" name="action" value="magellan_export" />
    <div class="postbox-container">
        <div class="metabox-holder">
            <div class="postbox">
                <h3 class="hndle"><span>Magellan Export Closed Data</span></h3>
                <div class="inside">
                    <p>
                        <label for="magellan_export_format"><strong>Export Format:</strong></label>
                    </p>
                    <p>
                        <select id="magellan_export_format" name="magellan_export_format">
                            <option value="csv-unique">CSV: Dates in distinct columns</option>
                            <option value="csv-single">CSV: Dates in single column</option>
                        </select>
                    </p>

                  <?php  submit_button("Export"); ?>
                </div>
            </div>
        </div>
    </div>
</form>
</div>