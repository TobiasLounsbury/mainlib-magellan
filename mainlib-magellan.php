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
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/TobiasLounsbury/mainlib-magellan
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
  die;
}

//Define the current version number
define( 'MAINLIB_MAGELLAN_VERSION', '1.0.0' );

add_filter( 'wpsl_store_category_args', 'mainlib_magellan_store_category_args');


function mainlib_magellan_store_category_args($args) {

  

  return $args;
}