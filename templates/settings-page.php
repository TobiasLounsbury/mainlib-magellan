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
                        <label for="mainlib_magellan_services_path"><strong>Path to Library Services Page:</strong></label>
                    </p>
                    <p>
                        <span><?php echo get_site_url(); ?>/</span><input type="text" id="mainlib_magellan_services_path" name="mainlib_magellan_services_path" value="<?php echo get_option('mainlib_magellan_services_path'); ?>" /><span>/</span>
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