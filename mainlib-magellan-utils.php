<?php


/**
 * Returns all of the Library Services in their sorted order
 *
 * @return array|int|WP_Error
 */
function mainlib_magellan_get_all_library_service_terms() {
  return get_terms( array(
      'taxonomy' => 'wpsl_store_category',
      'orderby' => "term_order",
      'hide_empty' => false,
  ) );
}