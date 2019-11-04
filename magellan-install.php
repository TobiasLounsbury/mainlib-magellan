<?php


/**
 * Create custom pages pre-loaded with the magellan shortcodes
 */
function mainlib_magellan_add_custom_pages() {

  $args = array(
      'name'        => "library-services",
      'post_type'   => 'page',
      'numberposts' => 1
  );
  $pages = get_posts($args);

  if(!$pages) {
    // Create post object
    $servicesPage = array(
        'post_title' => __('All Library Services', 'mainlib'),
        'post_name' => 'library-services',
        'post_content' => '[list-library-services]',
        'post_status' => 'publish',
        'post_author' => 1,
        'post_type' => 'page',
    );

    // Insert the post into the database
    wp_insert_post( $servicesPage );
  }
}

function mainlib_magellan_create_library_services_taxonomy_field_group() {
  if( function_exists('acf_add_local_field_group') ):

    acf_add_local_field_group(array(
        'key' => 'magellan_library_service_custom_fields',
        'title' => 'Library Services: Additional Metadata',
        'fields' => array(
            array(
                'key' => 'magellan_service_icon',
                'label' => 'Service Icon',
                'name' => 'service_icon',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => 'service_icon_selector',
                    'id' => '',
                ),
                'default_value' => 'fas fa-concierge-bell',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),
            array(
                'key' => 'magellan_default_service',
                'label' => 'Default Service',
                'name' => 'default_service',
                'type' => 'true_false',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => '',
                'ui_off_text' => '',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'taxonomy',
                    'operator' => '==',
                    'value' => 'wpsl_store_category',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
    ));

  endif;
}


/**
 * Add some default library services
 */
function mainlib_magellan_add_default_services() {
  $services = array(
      array("title" => "Printing", "slug" => "printing", "default" => true, "icon" => "fas fa-print", "description" => "Traditional 8.5×11 document to color photo printing"),
      array("title" => "Scanning", "slug" => "scanning", "default" => true, "icon" => "fas fa-file-pdf", "description" => "Providing patrons with the ability to scan documents"),
      array("title" => "Internet", "slug" => "internet", "default" => true, "icon" => "fas fa-wifi", "description" => "Free high speed wifi access to the world wide web"),
      array("title" => "Faxing",   "slug" => "faxing",   "default" => true, "icon" => "fas fa-fax", "description" => "Sending documents to phone numbers, the old way")
  );

  foreach($services as $service) {
    mainlib_magellan_populate_library_service_term($service);
  }
}


function mainlib_magellan_populate_library_service_term($service) {

  $term = wp_insert_term($service['title'], "wpsl_store_category", array("slug" => $service['slug'], "description" => $service['description']));
  if(is_array($term) && $term['term_id']) {
    update_field( 'magellan_service_icon', $service['icon'], "term_{$term['term_id']}");
    update_field( 'magellan_default_service', $service['default'], "term_{$term['term_id']}");
  }
}


/**
 * Alter some WPSL settings
 */
function mainlib_magellan_set_wpsl_default_settings() {
  global $wpdb;

  //Load the settings
  $settings = get_option("wpsl_settings");

  //Make Changes
  $settings['template_id'] = "mainlib";
  $settings['category_label'] = "Library Services";
  //$settings['permalink_slug'] = "libraries";
  //$settings['category_slug'] = "services";
  $settings['permalinks'] = 1;
  $settings['category_filter'] = 1;
  $settings['direction_redirect'] = 1;

  //Save settings
  //Don't use update_option because the built in sanitization
  //causes the settings to disappear.
  //update_option("wpsl_settings", $settings);
  $wpdb->update( $wpdb->options, array("option_value" => serialize($settings)), array( 'option_name' => "wpsl_settings" ) );
}