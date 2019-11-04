<?php

/**
 * Render the list of library services
 */
function mainlib_magellan_list_library_services_shortcode() {
  //Make sure we have the proper css included
  wp_enqueue_style("magellan", plugin_dir_url(__FILE__)."/css/magellan.css");

  //Start output buffering
  ob_start();

  //include the template
  include('templates/list-library-services.php');

  //Get the output
  $content = ob_get_contents();
  ob_end_clean();

  $content = apply_filters("magellan_after_service_list", $content);
  return $content;
}