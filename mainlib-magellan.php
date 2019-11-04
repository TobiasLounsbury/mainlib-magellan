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

require_once("mainlib-custom-metabox.php");
require_once("mainlib-magellan-shortcodes.php");
require_once("mainlib-magellan-wpsl-filters.php");

//Define the current version number
define( 'MAINLIB_MAGELLAN_VERSION', '1.0.0' );

register_activation_hook( __FILE__, 'mainlib_magellan_activate' );

//Add some Filter Hooks
add_filter( 'do_shortcode_tag', 'mainlib_magellan_shortcode_injection', 10, 4);
add_filter('template_include', 'mainlib_magellan_set_template');

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


//Shortcode for listing all the library services
add_shortcode( 'list-library-services', 'mainlib_magellan_list_library_services_shortcode');
add_shortcode( 'magellan', 'mainlib_magellan_magellan_shortcode');

//ACF Hooks
add_action('acf/init', 'mainlib_magellan_acf_add_local_field_groups');
add_action( 'acf/input/admin_enqueue_scripts', 'mainlib_magellan_acf_admin_enqueue_scripts' );

//Settings hooks
add_action( 'admin_init', 'mainlib_magellan_register_settings' );
add_action('admin_menu', 'mainlib_magellan_register_options_page');

/**
 * Take actions on plugin activation
 */
function mainlib_magellan_activate() {
  require_once("magellan-install.php");
  mainlib_magellan_add_custom_pages();
  mainlib_magellan_create_library_services_taxonomy_field_group();
  mainlib_magellan_add_default_services();
  mainlib_magellan_set_wpsl_default_settings();
}

/**
 * Creates the custom taxonomy fields for library services
 */
function mainlib_magellan_acf_add_local_field_groups() {
  require_once("magellan-install.php");
  mainlib_magellan_create_library_services_taxonomy_field_group();
}


/**
 *
 *
 * @param $output
 * @param $tag
 * @param $attr
 * @param $m
 * @return mixed
 */
function mainlib_magellan_shortcode_injection($output, $tag, $attr, $m) {
  //todo: Delete this function if we don't need it for anything else.
  if($tag == "wpsl") {
    //This is done via the UI now
    //return str_replace("Category filter", __( 'Library Services', 'mainlib' ), $output);
  }
  return $output;
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
  wp_enqueue_script("magellan", plugin_dir_url(__FILE__)."js/magellan-admin.js", array('jquery', 'select2'));
}


/**
 * Register the link to the settings page to the settings
 * sub-menu in the admin system
 */
function mainlib_magellan_register_options_page() {
  add_options_page('Magellan Settings', 'Magellan', 'manage_options', 'mainlib_mainlib', 'mainlib_magellan_options_page');
}

/**
 * Define the fields that are going to be rendered on the
 * settings page.
 */
function mainlib_magellan_register_settings() {
  add_option( 'mainlib_magellan_locations_path', 'locations');
  register_setting( 'mainlib_magellan_options_group', 'mainlib_magellan_locations_path');
}


/**
 * Render the settings page.
 */
function mainlib_magellan_options_page() {
  include(__DIR__."/templates/settings.php");
}
