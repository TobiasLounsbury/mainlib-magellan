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
        'post_content' => '[all-library-services]',
        'post_status' => 'publish',
        'post_author' => 1,
        'post_type' => 'page',
    );

    // Insert the post into the database
    wp_insert_post( $servicesPage );
  }
}


/**
 * Add some default library services
 */
function mainlib_magellan_add_default_services() {
  $services = array(
      array("title" => "Printing", "slug" => "printing", "default" => true, "order" => 1, "icon" => "fas fa-print", "description" => "Traditional 8.5×11 document to color photo printing"),
      array("title" => "Scanning", "slug" => "scanning", "default" => true, "order" => 4, "icon" => "fas fa-file-pdf", "description" => "Providing patrons with the ability to scan documents"),
      array("title" => "WiFi", "slug" => "wifi", "default" => true, "order" => 3, "icon" => "fas fa-wifi", "description" => "Free high speed wifi access to the world wide web"),
      array("title" => "Faxing",   "slug" => "faxing",   "default" => true, "order" => 2, "icon" => "fas fa-fax", "description" => "Sending documents to phone numbers, the old way")
  );

  foreach($services as $service) {
    mainlib_magellan_populate_library_service_term($service);
  }
}


/**
 * Create a new taxonomy item called from add default services
 *
 * @param $service
 */
function mainlib_magellan_populate_library_service_term($service) {

  $term = wp_insert_term($service['title'], "wpsl_store_category", array("term_order" => $service['order'], "slug" => $service['slug'], "description" => $service['description']));
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
  $settings['radius_label'] = "Search Radius";
  $settings['url_label'] = "URL";
  $settings['results_dropdown'] = 0;
  $settings['permalinks'] = 1;
  $settings['direction_redirect'] = 1;
  $settings['search_radius'] = '5,[10],20,30';

  $settings['category_filter'] = 1;
  $settings['category_filter_type'] = 'magellan';

  //These can't be changed here because the way we are saving
  // doesn't trigger the cascading update to the related urls
  //$settings['permalink_slug'] = "libraries";
  //$settings['category_slug'] = "services";

  //Save settings
  //Don't use update_option because the built in sanitization
  //causes the settings to disappear.
  //update_option("wpsl_settings", $settings);
  $wpdb->update( $wpdb->options, array("option_value" => serialize($settings)), array( 'option_name' => "wpsl_settings" ) );
}