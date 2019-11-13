<?php
/**
 * Filter functions for WPSL specific Filters
 */

/**
 * Function used to create new metadata fields for locations
 *
 * @param $meta
 * @return mixed
 */
function mainlib_magellan_wpsl_custom_meta_box_fields($meta) {

  //This is used to add new metadata to all library(store) locations
  //Saving of metadata is handled automagically by store locator.
  //$meta['Field Group Title'] = array('unique_field_key' => array("label" => "Title for the field"));
  //$meta['Library Services'] = array('3dprinterbrand' => array("label" => "3D Printer Brand"));

  return $meta;
}


/**
 * Alter the WPSL Cateogry taxonomy to conform to the Magellan requirements
 *
 * @param $args
 * @return mixed
 */
function mainlib_magellan_wpsl_store_category_args($args) {

  //This pair of arguments changes the interface to the default
  //tag-cloud style in base wordpress.
  //WARNING: Do not enable hierarchical without changing the interface type
  // it screws up the interface and you get odd numeric tags showing up instead of
  // the selections made.
  //$args['show_tagcloud'] = true;
  //$args['hierarchical'] = false;

  //Uncomment to enable custom meta-box callback for the wpsl sidebar form
  //$args['meta_box_cb'] = "mainlib_magellan_custom_meta_box";

  $args['labels'] = array(
      'name'              => __( 'Library Services', 'mainlib' ),
      'singular_name'     => __( 'Library Service', 'mainlib' ),
      'search_items'      => __( 'Search Store Categories', 'mainlib' ),
      'all_items'         => __( 'All Library Services', 'mainlib' ),
      'parent_item'       => __( 'Parent Library Services', 'mainlib' ),
      'parent_item_colon' => __( 'Parent Library Services:', 'mainlib' ),
      'edit_item'         => __( 'Edit Library Service', 'mainlib' ),
      'update_item'       => __( 'Update Library Service', 'mainlib' ),
      'add_new_item'      => __( 'Add New Library Service', 'mainlib' ),
      'new_item_name'     => __( 'New Library Service Name', 'mainlib' ),
      'menu_name'         => __( 'Library Services', 'mainlib' ),
  );

  return $args;
}


/**
 * Registers the Magellan template with WPSL
 * It must still be selected in the settings UI before
 * it is active
 *
 * @param $templates
 * @return array
 */
function mainlib_magellan_wpsl_custom_templates( $templates ) {

  $templates[] = array (
      'id'   => 'mainlib',
      'name' => 'Magellan Template',
      'path' => __DIR__. '/' . 'templates/wpsl-custom-template.php',
  );

  return $templates;
}



/**
 * Alter the underscore template used to generate the results listings
 *
 * @param $listing_template
 * @return mixed
 */
function mainlib_magellan_wpsl_custom_listing_template($listing_template) {
  ob_start();
  include (__DIR__."/templates/wpsl-listing.php");
  $myTemplate = ob_get_clean();

  return ($myTemplate) ? $myTemplate : $listing_template;
}


/**
 *
 *
 * @param $template
 * @return mixed
 */
function mainlib_magellan_wpsl_custom_cpt_info_window_template($template) {

  return $template;
}


/**
 * @param $template
 * @return mixed
 */
function mainlib_magellan_wpsl_custom_more_info_template($template) {

  return $template;
}


/**
 * Modifies the
 *
 * @param $template
 * @return mixed
 */
function mainlib_magellan_wpsl_custom_store_header_template($template) {
  return $template;
}

/**
 * Include the services
 *
 * @param $meta
 * @param $id
 * @return mixed
 */
function mainlib_magellan_wpsl_custom_store_meta($meta, $id) {
  //$meta['thumb'] = get_the_post_thumbnail( $id, $size = apply_filters( 'wpsl_thumb_size', array( 45, 45 ) ), apply_filters( 'wpsl_thumb_attr', $attr ) );
  $meta['services'] = array();
  $terms = wp_get_post_terms($id, "wpsl_store_category");
  foreach($terms as $term) {
    $meta['services'][] = $term->slug;
  }
  return $meta;
}

/**
 * @param $size
 * @return mixed
 */
function mainlib_magellan_wpsl_thumb_size($size) {
  return array(500, 500);
  //return $size;
}

/**
 * @param $attr
 * @return mixed
 */
function mainlib_magellan_wpsl_thumb_attr($attr) {
  $attr['class'] = "library-listing-image";
  return $attr;
}


/**
 * Alter the Store-Locator post type metadata to refer to locations
 * as Libraries rather than stores
 *
 * @param $labels
 * @return mixed
 */
function mainlib_magellan_wpsl_post_type_labels($labels) {

  $labels['name'] = 'Magellan';

  $labels['add_new'] = 'New Library';
  $labels['add_new_item'] = 'Add New Library';
  $labels['all_items'] = 'All Libraries';
  $labels['edit_item'] = 'Edit Library';
  $labels['new_item'] = 'New Library';
  $labels['not_found'] = 'No Libraries Found';
  $labels['not_found_in_trash'] = 'No Libraries found in trash';
  $labels['search_items'] = 'Search Libraries';
  $labels['singular_name'] = 'Library';
  $labels['view_item'] = 'View Libraries';

  return $labels;
}






