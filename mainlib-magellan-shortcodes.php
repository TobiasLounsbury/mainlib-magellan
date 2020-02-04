<?php

/**
 * Render the list of library services
 */
function mainlib_magellan_all_library_services_shortcode() {
  //Make sure we have the proper css included
  wp_enqueue_style("magellan", plugin_dir_url(__FILE__)."/css/magellan.css");

  //Start output buffering
  ob_start();

  //include the template
  include('templates/all-library-services-shortcode.php');

  //Get the output
  $content = ob_get_contents();
  ob_end_clean();

  $content = apply_filters("magellan_after_service_list", $content);
  return $content;
}


/**
 * Render the list of services for a single library
 * Used on the View:Post screen by default
 *
 * @param array $attr
 * @return false|string|void
 */
function mainlib_magellan_library_services_shortcode($attr = array()) {

  global $wpsl_settings, $post;

  $attr = ($attr) ? $attr: array();

  if ( get_post_type() == 'wpsl_stores' ) {
    if ( empty( $attr['id'] ) ) {
      if ( isset( $post->ID ) ) {
        $attr['id'] = $post->ID;
      } else {
        return;
      }
    }
  } else if ( empty( $attr['id'] ) ) {
    return 'If you use the [library-services] shortcode outside a library page you need to set the ID attribute.';
  }

  //Make sure we have the proper css included
  wp_enqueue_style("magellan", plugin_dir_url(__FILE__)."/css/magellan.css");
  wp_enqueue_script("magellan", plugin_dir_url(__FILE__)."js/magellan.js", array('jquery'));

  //Start output buffering
  ob_start();

  $postId = $attr['id'];
  //include the template
  include('templates/library-services-shortcode.php');

  //Get the output
  $content = ob_get_contents();
  ob_end_clean();
  return $content;
}