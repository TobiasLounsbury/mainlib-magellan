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


function mainlib_magellan_wpsl_store_search() {
    if ( isset( $_GET['filter'] ) && $_GET['filter'] ) {
        $_GET['mainlib_filter'] = $_GET['filter'];
        unset($_GET['filter']);
    }
}

function mainlib_magellan_wpsl_sql($sql) {

    if ( isset( $_GET['mainlib_filter'] ) && $_GET['mainlib_filter'] ) {
        $filter_ids = array_map( 'absint', explode( ',', $_GET['mainlib_filter'] ) );
        $union = get_option('mainlib_magellan_category_union', 'AND');
        $join = "LEFT JOIN (SELECT object_id, (1) as has_term_%tid% FROM wp_term_relationships WHERE term_taxonomy_id = %tid%) as ht%tid% ON ht%tid%.object_id=posts.ID";
        $wheres = [];
        $joins = [];
        foreach($filter_ids as $fid) {
            $joins[] = str_replace("%tid%", $fid, $join);
            $wheres[] = "has_term_{$fid} = 1";
        }
        $where = "\n". implode("\n", $joins). "\n WHERE ( ". implode(" {$union} ", $wheres) ." ) AND ";
        $sql = str_replace("WHERE ", $where, $sql);
    }

    return $sql;
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
 * Include the services in the location metadata
 *
 * @param $meta
 * @param $id
 * @return mixed
 */
function mainlib_magellan_wpsl_custom_store_meta($meta, $id) {
  $meta['services'] = array();
  $terms = wp_get_post_terms($id, "wpsl_store_category");
  foreach($terms as $term) {
    $meta['services'][] = $term->slug;
  }
  return $meta;
}


/**
 * Set the max render size of a location thumb
 *
 * @param $size
 * @return mixed
 */
function mainlib_magellan_wpsl_thumb_size($size) {
  return array(500, 500);
  //return $size;
}


/**
 * Add a class to the library thumb
 *
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


/**
 * Add the Export menu item to the Store Locator sub-menu
 *
 * @param $items
 * @return mixed
 */
function mainlib_magellan_wpsl_sub_menu_items($items) {

  //Todo: Should this be moved to its own tab under settings?

  $items[] = array(
    "caps" => "manage_wpsl_settings",
    "function" => "mainlib_magellan_render_export_page",
    "menu_slug" => "mainlib_export",
    "menu_title" => "Closed Dates Export",
    "page_title" => "MAIN Library Closed Dates Export",
  );

  return $items;
}


/**
 * Add Magellan Custom settings as a tab to the Store
 * Locator settings page
 *
 * @param $tabs
 * @return mixed
 */
function mainlib_magellan_wpsl_add_custom_settings_tab($tabs) {
  $tabs['magellan'] = "Magellan Custom Settings";
  return $tabs;
}


/**
 * Render the Magellan custom settings form if that is the
 * selected tab
 *
 * @param $tab
 */
function mainlib_magellan_wpsl_render_custom_settings_tab($tab) {
  if($tab == "magellan") {
    mainlib_magellan_render_settings_page();
  }
}


/**
 * Add Magellan custom widget as a filter-type option
 * for the category filter
 *
 * @param $options
 * @return mixed
 */
function mainlib_magellan_wpsl_setting_dropdowns($options) {
  $options['filter_types']['values']['magellan'] = "Magellan Custom Widget";
  return $options;
}


/**
 * Make sure that when a user attempt to set the category widget to "magellan"
 * it sticks.
 *
 * @param $value
 * @param $old_value
 * @param $option
 * @return mixed
 */
function mainlib_magellan_wpsl_settings_update($value, $old_value, $option) {
  if(array_key_exists("wpsl_search", $_REQUEST) && array_key_exists("category_filter_type", $_REQUEST['wpsl_search']) && $_REQUEST['wpsl_search']['category_filter_type'] == "magellan") {
    $value['category_filter_type'] = "magellan";
  }
  return $value;
}


/**
 * Replace the wp-store-locator primary javascript file
 * with our own, that is identical except for a front-end query filter
 * added at line 1718 to the collectAjaxData() function
 *
 * @param $filename
 * @return string
 */
function mainlib_magellan_wpsl_gmap_js($filename) {
  $min = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
  return plugin_dir_url(__FILE__)."js/wpsl-gmap${min}.js";
  //return $filename;
}