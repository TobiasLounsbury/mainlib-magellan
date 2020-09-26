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

                    <p>
                        <label for="mainlib_magellan_category_union"><strong>Library Service Search Method:</strong></label>
                    </p>
                    <p>
                        <select id="mainlib_magellan_category_union" name="mainlib_magellan_category_union">
                            <option value="AND" <?php echo (get_option('mainlib_magellan_category_union') == "AND") ? "selected" : ""; ?>>Show only libraries matching ALL selected services (AND)</option>
                            <option value="OR" <?php echo (get_option('mainlib_magellan_category_union') == "OR") ? "selected" : ""; ?>>Show libraries mathcing ANY of the selected services (OR)</option>
                        </select>
                    </p>

                    <hr>

                    <p>
                        <label for="mainlib_magellan_special_hours_warning_text"><strong>Library Location Special Hours Text:</strong></label>
                    </p>
                    <p>
                        <textarea id="mainlib_magellan_special_hours_warning_text" name="mainlib_magellan_special_hours_warning_text"><?php echo get_option('mainlib_magellan_special_hours_warning_text'); ?></textarea>
                    </p>

                    <hr>

                    <p><label for="mainlib_magellan_services_radius"><strong>Search Radius when arriving from Library Services Page:</strong></label>
                    <input type="text" id="mainlib_magellan_services_radius" name="mainlib_magellan_services_radius" class="magellan-small-input" value="<?php echo get_option('mainlib_magellan_services_radius', '30'); ?>" /></p>


                    <p><label for="mainlib_magellan_icon_limit"><strong>Max Number of Service icons to show in search:</strong></label>
                    <input type="text" id="mainlib_magellan_icon_limit" name="mainlib_magellan_icon_limit" class="magellan-small-input" value="<?php echo get_option('mainlib_magellan_icon_limit', '8'); ?>" /></p>



                    <p>
                        <label for="mainlib_magellan_show_default_services_in_search"><strong>Show Default Services in Search Results:</strong></label>
                        <input type="checkbox" id="mainlib_magellan_show_default_services_in_search" name="mainlib_magellan_show_default_services_in_search" value="1" <?php echo (get_option('mainlib_magellan_show_default_services_in_search') == 1) ? "checked" : ""; ?> />
                    </p>

                    <p>
                        <label for="mainlib_magellan_hide_create_services"><strong>Disable Add New Service:</strong></label>
                        <input type="checkbox" id="mainlib_magellan_hide_create_services" name="mainlib_magellan_hide_create_services" value="1" <?php echo (get_option('mainlib_magellan_hide_create_services') == 1) ? "checked" : ""; ?> />
                    </p>

                    <hr>

                    <p><label><strong>Hint Texts:</strong></label></p>

                    <p><label for="mainlib_magellan_services_hint"><strong>Navigation Icon:</strong></label>
                    <input type="text" id="mainlib_magellan_services_hint" name="mainlib_magellan_services_hint" value="<?php echo get_option('mainlib_magellan_services_hint'); ?>" /></p>

                    <p><label for="mainlib_magellan_navigation_hint"><strong>Navigation Icon:</strong></label>
                    <input type="text" id="mainlib_magellan_navigation_hint" name="mainlib_magellan_navigation_hint" value="<?php echo get_option('mainlib_magellan_navigation_hint'); ?>" /></p>

                    <p><label for="mainlib_magellan_call_hint"><strong>Call Icon:</strong></label>
                    <input type="text" id="mainlib_magellan_call_hint" name="mainlib_magellan_call_hint" value="<?php echo get_option('mainlib_magellan_call_hint'); ?>" /></p>

                    <p><label for="mainlib_magellan_more_info_hint"><strong>More Info Icon:</strong></label>
                    <input type="text" id="mainlib_magellan_more_info_hint" name="mainlib_magellan_more_info_hint" value="<?php echo get_option('mainlib_magellan_more_info_hint'); ?>" /></p>

                    <p><label for="mainlib_magellan_map_view_hint"><strong>Map View Icon:</strong></label>
                    <input type="text" id="mainlib_magellan_map_view_hint" name="mainlib_magellan_map_view_hint" value="<?php echo get_option('mainlib_magellan_map_view_hint'); ?>" /></p>

                    <p><label for="mainlib_magellan_list_view_hint"><strong>List View Icon:</strong></label>
                    <input type="text" id="mainlib_magellan_list_view_hint" name="mainlib_magellan_list_view_hint" value="<?php echo get_option('mainlib_magellan_list_view_hint'); ?>" /></p>


                  <?php  submit_button(); ?>
                </div>
            </div>
        </div>
    </div>
</form>