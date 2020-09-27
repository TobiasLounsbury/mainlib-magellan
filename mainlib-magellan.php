<?php
/**
 * @package   MAINLIB_MAGELLAN
 * @author    Tobias Lounsbury <TobiasLounsbury@gmail.com>
 * @license   GPL-2.0+
 * @link      https://github.com/TobiasLounsbury/mainlib-magellan
 * @copyright 2019 Tobias Lounsbury
 *
 * @wordpress-plugin
 * Plugin Name:       MAIN Magellan
 * Plugin URI:        https://github.com/TobiasLounsbury/mainlib-magellan
 * Description:       Custom WordPress modifications for the MAIN-Library Magellan project
 * Version:           1.0.0
 * Author:            Tobias Lounsbury
 * Author URI:        http://TobiasLounsbury.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       mainlib
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/TobiasLounsbury/mainlib-magellan
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
  die;
}


require_once("mainlib-magellan-shortcodes.php");
require_once("mainlib-magellan-wpsl-filters.php");
require_once("mainlib-magellan-utils.php");

//Define the current version number
define( 'MAINLIB_MAGELLAN_VERSION', '1.0.0' );

//Plugin Activation Hook
register_activation_hook( __FILE__, 'mainlib_magellan_activate' );

//Add some Filter Hooks
add_filter('template_include', 'mainlib_magellan_set_template');

//Store Locator Hooks
add_filter( 'wpsl_store_category_args', 'mainlib_magellan_wpsl_store_category_args');
add_filter( 'wpsl_meta_box_fields', 'mainlib_magellan_wpsl_custom_meta_box_fields' );
add_filter( 'wpsl_templates', 'mainlib_magellan_wpsl_custom_templates' );
add_filter( 'wpsl_listing_template', 'mainlib_magellan_wpsl_custom_listing_template' );
add_filter( 'wpsl_more_info_template', 'mainlib_magellan_wpsl_custom_more_info_template' );
add_filter( 'wpsl_store_header_template', 'mainlib_magellan_wpsl_custom_store_header_template' );
add_filter( 'wpsl_cpt_info_window_template', 'mainlib_magellan_wpsl_custom_cpt_info_window_template' );
add_filter( 'wpsl_thumb_size', 'mainlib_magellan_wpsl_thumb_size' );
add_filter( 'wpsl_thumb_attr', 'mainlib_magellan_wpsl_thumb_attr' );
add_filter( 'wpsl_store_meta', 'mainlib_magellan_wpsl_custom_store_meta', 10, 2 );
add_filter( 'wpsl_post_type_labels', 'mainlib_magellan_wpsl_post_type_labels');
add_filter( 'wpsl_sub_menu_items', 'mainlib_magellan_wpsl_sub_menu_items');
add_filter( 'wpsl_enable_styled_dropdowns', '__return_false' );
add_filter( 'wpsl_setting_dropdowns', 'mainlib_magellan_wpsl_setting_dropdowns');
add_filter( 'wpsl_gmap_js', 'mainlib_magellan_wpsl_gmap_js');
add_filter( 'pre_update_option_wpsl_settings', 'mainlib_magellan_wpsl_settings_update', 10, 3);

add_filter( 'wpsl_sql', 'mainlib_magellan_wpsl_sql');
add_action( 'wp_ajax_store_search', 'mainlib_magellan_wpsl_store_search', 9);
add_action( 'wp_ajax_nopriv_store_search', 'mainlib_magellan_wpsl_store_search', 9);


add_filter( 'wpsl_settings_tab', 'mainlib_magellan_wpsl_add_custom_settings_tab');
add_filter( 'wpsl_settings_section', 'mainlib_magellan_wpsl_render_custom_settings_tab');


//
add_action( 'add_meta_boxes', "mainlib_magellan_alter_metaboxes", 99);

//Shortcode for listing all the library services
add_shortcode( 'all-library-services', 'mainlib_magellan_all_library_services_shortcode');
add_shortcode( 'library-services', 'mainlib_magellan_library_services_shortcode');

//ACF Hooks
add_action('acf/init', 'mainlib_magellan_acf_add_local_field_groups');
add_action( 'acf/input/admin_enqueue_scripts', 'mainlib_magellan_acf_admin_enqueue_scripts' );


//Settings hooks
add_action( 'admin_init', 'mainlib_magellan_hook_admin_init' );
add_action( 'admin_menu', 'mainlib_magellan_hook_admin_menu', 110);


//Hook to alter term order on creation
add_action('wp_insert_term_data', 'mainlib_magellan_add_service', 10, 3);

//Hooks for adding custom column to library services taxonomy listing admin form
add_filter("manage_edit-wpsl_store_category_columns", 'mainlib_magellan_add_order_column');
add_filter("manage_edit-wpsl_store_category_sortable_columns", 'mainlib_magellan_add_order_sortable_column');
add_filter("manage_wpsl_store_category_custom_column", 'mainlib_magellan_add_order_data_to_column', 10, 3);


//Hook that lets me hook in and add the library services to the content stream
add_filter("the_content", 'mainlib_magellan_add_services_to_post_content');

//Hook to forward library users directly to the dashboard
add_action( 'login_redirect', 'mainlib_magellan_login_redirect', 99, 3);


/**
 * Take actions on plugin activation
 */
function mainlib_magellan_activate() {
  require_once("mainlib-magellan-install.php");
  mainlib_magellan_add_custom_pages();
  mainlib_magellan_create_library_services_taxonomy_field_group();
  mainlib_magellan_add_default_services();
  mainlib_magellan_set_wpsl_default_settings();
}

/**
 * Creates the custom taxonomy fields for library services
 */
function mainlib_magellan_acf_add_local_field_groups() {
  mainlib_magellan_create_library_services_taxonomy_field_group();
}


/**
 * Alter the template file for the wpsl category listing
 * defaults to archive.php
 *
 * @param $template
 * @return string
 */
function mainlib_magellan_set_template( $template ) {
  global $wpsl_settings;

  if(is_tax('wpsl_store_category')) {

    wp_enqueue_style("magellan", plugin_dir_url(__FILE__)."css/magellan.css");

    //If a taxonomy-services template has already been found use that
    if( 1 == preg_match('/^taxonomy-services((-(\S*))?).php/', basename($template)) )
      return $template;

    //If a custom wpsl-stores_category template has already been found use that.
    if( 1 == preg_match('/^taxonomy-wpsl_store_category((-(\S*))?).php/', basename($template)) )
      return $template;

    //if the store category slug has been changed to something other than "services" and has a custom template use it
    if( 1 == preg_match('/^taxonomy-'.$wpsl_settings['category_slug'].'((-(\S*))?).php/', basename($template)) )
      return $template;

    //Check if the current theme has a custom template for store categories and if so use it
    $template = locate_template(array("taxonomy-{$wpsl_settings['category_slug']}.php", "taxonomy-services.php", "taxonomy-wpsl_store_category.php"));
    if(!$template) {
      //fallback on our included template
      $template = __DIR__ .'/templates/taxonomy-services.php';
    }
  }

  return $template;
}


/**
 * Function to include the Magellan js and css on the edit
 * library services taxonomy form
 */
function mainlib_magellan_acf_admin_enqueue_scripts() {
  wp_enqueue_style('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css' );
  wp_enqueue_script('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js', array('jquery') );

  wp_enqueue_style("magellan", plugin_dir_url(__FILE__)."css/magellan.css");
  wp_enqueue_script("magellan-admin", plugin_dir_url(__FILE__)."js/magellan-admin.js", array('jquery', 'select2'));

    if (get_option('mainlib_magellan_hide_create_services') == 1) {
        wp_enqueue_script("magellan-disable-services", plugin_dir_url(__FILE__)."js/magellan-disable-new-services.js", array('magellan-admin'));
        wp_enqueue_style("magellan-disable-services-css", plugin_dir_url(__FILE__)."css/magellan-disable-new-services.css");
    }

}


/**
 * Handles the Admin init hook
 *
 * Used to define the fields that are going to be rendered on the
 * settings page.
 *
 * And as a start hook for the export functionality
 *
 */
function mainlib_magellan_hook_admin_init() {
  //Register fields for Settings page
  add_option( 'mainlib_magellan_locations_path', 'locations');
  register_setting( 'mainlib_magellan_options_group', 'mainlib_magellan_locations_path');

  add_option( 'mainlib_magellan_services_path', 'library-services');
  register_setting( 'mainlib_magellan_options_group', 'mainlib_magellan_services_path');

  add_option( 'mainlib_magellan_default_icon', 'fas fa-concierge-bell');
  register_setting( 'mainlib_magellan_options_group', 'mainlib_magellan_default_icon');

    add_option( 'mainlib_magellan_hide_create_services', "1");
    register_setting( 'mainlib_magellan_options_group', 'mainlib_magellan_hide_create_services');

    add_option( 'mainlib_magellan_show_default_services_in_search', "1");
    register_setting( 'mainlib_magellan_options_group', 'mainlib_magellan_show_default_services_in_search');

    add_option( 'mainlib_magellan_services_radius', '30');
    register_setting( 'mainlib_magellan_options_group', 'mainlib_magellan_services_radius');

    add_option( 'mainlib_magellan_icon_limit', '8');
    register_setting( 'mainlib_magellan_options_group', 'mainlib_magellan_icon_limit');

    add_option( 'mainlib_magellan_category_union', 'AND');
    register_setting( 'mainlib_magellan_options_group', 'mainlib_magellan_category_union');


  //Text Options
  add_option( 'mainlib_magellan_services_hint', 'View more services (if available)');
  register_setting( 'mainlib_magellan_options_group', 'mainlib_magellan_services_hint');

  add_option( 'mainlib_magellan_navigation_hint', 'Open in Google Maps');
  register_setting( 'mainlib_magellan_options_group', 'mainlib_magellan_navigation_hint');

  add_option( 'mainlib_magellan_call_hint', 'Call this Library');
  register_setting( 'mainlib_magellan_options_group', 'mainlib_magellan_call_hint');

  add_option( 'mainlib_magellan_more_info_hint', 'Show Hours and More Details');
  register_setting( 'mainlib_magellan_options_group', 'mainlib_magellan_more_info_hint');

  add_option( 'mainlib_magellan_map_view_hint', 'Map View');
  register_setting( 'mainlib_magellan_options_group', 'mainlib_magellan_map_view_hint');

  add_option( 'mainlib_magellan_list_view_hint', 'List View');
  register_setting( 'mainlib_magellan_options_group', 'mainlib_magellan_list_view_hint');

  add_option( 'mainlib_magellan_special_hours_warning_text', '<span><strong>This library has special hours today</strong></span>');
  register_setting( 'mainlib_magellan_options_group', 'mainlib_magellan_special_hours_warning_text');

  add_option( 'mainlib_magellan_is_currently_closed_text', '<span><strong>Currently Closed</strong></span>');
  register_setting( 'mainlib_magellan_options_group', 'mainlib_magellan_is_currently_closed_text');


  //Handle Closed Data Export
  if ( array_key_exists( 'action', $_REQUEST ) && $_REQUEST['action'] == 'magellan_export') {
    require_once("mainlib-magellan-export.php");
    mainlib_magellan_export_closed_data();
  }
}


/**
 * Render the settings page.
 */
function mainlib_magellan_render_settings_page() {
  include(__DIR__."/templates/settings-page.php");
}


/**
 * Render the Closures Export page.
 */
function mainlib_magellan_render_export_page() {
  include(__DIR__."/templates/export-page.php");
}

/**
 * Redirect user after successful login.
 *
 * @param string $redirect_to URL to redirect to.
 * @param string $request URL the user is coming from.
 * @param object $user Logged user's data.
 * @return string
 */
function mainlib_magellan_login_redirect( $redirect_to, $request, $user ) {
  if ( isset( $user->roles ) && is_array( $user->roles ) ) {
    if ( in_array( 'wpsl_store_coauthor', $user->roles ) ) {
      // redirect them to the dashboard
      wp_redirect(get_dashboard_url($user->id));
      exit;
    }
  }
  return $redirect_to;
}




/**
 * Used to alter the Metaboxes on the wpsl edit screen to say library instead of store
 *
 * Also used to remove the jquery/javascript box
 *
 */
function mainlib_magellan_alter_metaboxes() {
  global $wp_meta_boxes;

  if(array_key_exists("wpsl_stores", $wp_meta_boxes)) {
    try {
      //Remove the jQuery Javascript stuff - It isn't needed for store locations
      unset($wp_meta_boxes['wpsl_stores']['normal']['default']['WCP_script']);

      //Change the Metabox Titles
      $wp_meta_boxes['wpsl_stores']['normal']['high']['wpsl-store-details']['title'] = "Library Details";
      $wp_meta_boxes['wpsl_stores']['side']['default']['wpsl-map-preview']['title'] = "Library Map";
    } catch (Exception $exception) {
      //Don't care, just don't want an error thrown.
    }
  }
}


/**
 * Creates a new custom field group programatically
 * that is used for storing additional metadata within the
 * library services taxonomy
 *
 */
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
                'default_value' => get_option('mainlib_magellan_default_icon', 'fas fa-concierge-bell'),
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
 * Change the Admin menu title for re-ordering
 * the Library Services Taxonomy
 *
 * Register the link to the settings page to the settings
 * sub-menu in the admin system
 *
 */
function mainlib_magellan_hook_admin_menu() {
  global $submenu;

  if(array_key_exists("edit.php?post_type=wpsl_stores", $submenu)) {
    foreach($submenu['edit.php?post_type=wpsl_stores'] as &$m) {
      if($m[1] == "manage_options") {
        $m[0] = "Reorder Library Services";
      }
    }
  }

}


/**
 * Alter the term_order of newly created terms so that no defined
 * order results in the bottom of the list.
 *
 * @param $data
 * @param $taxonomy
 * @param $args
 * @return mixed
 */
function mainlib_magellan_add_service($data, $taxonomy, $args) {
  if($taxonomy == "wpsl_store_category" && !array_key_exists("term_order", $data) && $args['parent'] == 0) {
    $data['term_order'] = wp_count_terms( $taxonomy, array('hide_empty'=> false, 'parent'    => 0));
  }
  return $data;
}


/**
 * Add a new "Display Order" column to the admin listing for library services
 *
 * @param $columns
 * @return mixed
 */
function mainlib_magellan_add_order_column($columns) {
  $columns['term_order'] = "Display Order";
  return $columns;
}


/**
 * Make the Display Order /term_order column sortable
 * and tell WP to use the term_order key when sorting
 *
 * @param $columns
 * @return mixed
 */
function mainlib_magellan_add_order_sortable_column($columns) {
  $columns['term_order'] = "term_order";
  return $columns;
}


/**
 * Output the Display Order (term_order) to our admincolumn
 *
 * @param $s - Empty String
 * @param $column - Column Name
 * @param $term_id - Term Id
 */
function mainlib_magellan_add_order_data_to_column($s, $column, $term_id) {
  if ($column == 'term_order') {
    $term = get_term($term_id, "wpsl_store_category");
    echo $term->term_order;
  }
}


/**
 * Adds the [library-services] shortcode to the content stream
 * for the store-locator post type.
 *
 * @param $content
 * @return string
 */
function mainlib_magellan_add_services_to_post_content( $content ) {
  global $wpsl_settings, $post;

  if ( isset( $post->post_type ) && $post->post_type == 'wpsl_stores' && is_single() && in_the_loop() ) {

    //todo: Load and javascript we might need.
    $pos = strpos($content, '<div class="wpsl-locations-details">');
    if($pos) {
      $content = substr($content, 0, $pos). "[library-services]\n". substr($content, $pos);
    } else {
      $content =  "[library-services]". $content;
    }
  }

  return $content;
}

